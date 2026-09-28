<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use Hash;
use Mail;
use File;
use DB;
use Session;
use Cookie;
use Validator;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\PostExtra;
use App\Models\Review;
use App\Models\General;
use App\Models\Country;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use GuzzleHttp\Client;
use App\Mail\RegistrationMail;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{

 	// Product management Function
     public function products(Request $r){
      
//       //Order Data
//       $oldOrders =DB::table('wpzz_posts')->where('post_type','shop_order')
//       ->limit(200)
//       ->get();
      
//       foreach($oldOrders as $order){
          
//       }
      
      
//       return $oldOrders;
      
      
//       //User Data
//       $oldUsers =DB::table('wpzz_users')
//       ->limit(10)
//       ->get();
      
      
//       foreach($oldUsers as $user){
//           $userDatas =DB::table('wpzz_usermeta')
//                           //->where('post_type','product')
//                           ->where('user_id',$user->ID)
//                           //->limit(10)
//                           ->get();
                          
//             return $userDatas;
//       }
      
//       return $oldUsers;
      
      
//       // Products Data      
//       $oldProducts =DB::table('wpzz_posts')->where('post_type','product')
//       ->limit(10)
//       ->get();
      
//       foreach($oldProducts as $product){
            
//             $attachment =DB::table('wpzz_posts')
//                           //->where('post_type','product')
//                           ->where('post_parent',$product->ID)
//                           ->limit(10)
//                           ->get();
//             $metaPosts =DB::table('wpzz_postmeta')
//                         ->where('post_id',$product->ID)
//                         ->limit(10)
//                         ->get();
            
//             $realations = DB::table('wpzz_term_relationships')
//                         ->where('object_id',$product->ID)
//                         ->limit(10)
//                         ->get();
//             //return    $realations;
//             $realItems =array();
            
//             foreach($realations as $i=>$realation){
                
//                 $realItem =DB::table('wpzz_term_taxonomy')
//                         ->where('term_taxonomy_id',$realation->term_taxonomy_id)
//                         ->limit(10)
//                         ->get();
//                 //return $realItem;
//                 $realItems[] = $realItem;
                    
//             }
            
//             return $realItems;
            
            
//           //return $metaPosts;
//           //return $attachment;
//           return $realations;
//           return $product;
//      }
      
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['products']['all']);
      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Post::latest()->where('type',2)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

          if($allPer && $data->addedby_id!=Auth::id()){
            // You are unauthorized Try!!
          }else{

            if($r->action==1){
              $data->status='active';
              $data->save();
            }elseif($r->action==2){
              $data->status='inactive';
              $data->save();
            }elseif($r->action==3){
              $data->fetured=true;
              $data->save();
            }elseif($r->action==4){
              $data->fetured=false;
              $data->save();
            }elseif($r->action==5){
              
              $medias =Media::latest()->where('src_type',1)->where('src_id',$data->id)->get();
              foreach($medias as $media){
                if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
                }
                $media->delete();
              }

              $data->productCtgs()->delete();
              $data->delete();
            }

          }

        }

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

      //Filter Action End
      
        
      $products =Post::latest()->where('type',2)
        ->where(function($q) use ($r,$allPer) {

            if($r->search){
                $q->where('search_key','LIKE','%'.$r->search.'%');
            }
            
            if($r->startDate || $r->endDate)
            {
                if($r->startDate){
                    $from =$r->startDate;
                }else{
                    $from=Carbon::now()->format('Y-m-d');
                }

                if($r->endDate){
                    $to =$r->endDate;
                }else{
                    $to=Carbon::now()->format('Y-m-d');
                }

                $q->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to);
            }

            if($r->status){
             $q->where('status',$r->status); 
            }

            // Check Permission
            if($allPer){
             $q->where('addedby_id',auth::id()); 
            }


        })
        ->select(['id','name','final_price','slug','view','type','brand_id','created_at','addedby_id','status','fetured'])
        ->paginate(25)->appends([
          'search'=>$r->search,
          'status'=>$r->status,
          'startDate'=>$r->startDate,
          'endDate'=>$r->endDate,
        ]);

        //Total Count Results
        $totals = DB::table('posts')
        ->where('type',2)
        ->selectRaw('count(*) as total')
        ->selectRaw("count(case when status = 'active' then 1 end) as active")
        ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
        ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
        ->first();

        return view('admin.products.productsAll',compact('products','totals','r'));
    }

    public function productsCreate(){

        $product =new Post();
        $product->type =2;
        $product->status ='temp';
        $product->addedby_id =Auth::id();
        $product->save();

        return redirect()->route('admin.productsEdit',$product->id);

    }

    public function productsEdit(Request $r,$id){
      $product =Post::where('type',2)->find($id);
      if(!$product){
        Session()->flash('error','This Product Are Not Found');
        return redirect()->route('admin.products');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['products']['all']);
      if($allPer && $product->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.products');
      }


      $categories =Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->get();
      $brands =Attribute::where('type',2)->where('status','<>','temp')->where('parent_id',null)->get();
      $attributes =Attribute::where('type',9)->where('status','<>','temp')->where('parent_id',null)->get();
      $tags =Attribute::where('type',10)->where('status','<>','temp')->where('parent_id',null)->get();

      $attriMessage ='';
      $selectSku =array();

      

      if($r->skus){
        //return $r;

        for ($i=0; $i < count($r->skus); $i++) { 
          
          $result = $r->skus[$i];
          if($result){

            $result_explode = explode('|', $result);

            $list =array('item'=>$result_explode[0],'skuid'=>$result_explode[1]);

            $selectSku =array(
                'items' => $list,
            );

          }


        }
        
        //return $result_explode[1];
      }

  
      $selectItems ='';
      
      return view('admin.products.productsEdit',compact('product','categories','brands','attributes','tags','attriMessage','selectSku','selectItems','r'));
    }

    public function productsUpdate(Request $r,$id){
        $product =Post::where('type',2)->find($id);
        if(!$product){
        Session()->flash('error','This Product Are Not Found');
        return redirect()->route('admin.products');
        }


        $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:250',
            'catagoryid.*' => 'nullable|numeric',
            'product_type' => 'nullable|numeric',
            'brand' => 'nullable|numeric',
            'tags.*' => 'nullable|numeric',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'gallery_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
      
        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }

       
        $product->name=$r->name;
        $product->short_description=$r->short_description;
        $product->description=$r->description;
        $product->seo_title=$r->seo_title;
        $product->seo_desc=$r->seo_desc;
        $product->seo_keyword=$r->seo_keyword;
        $product->brand_id=$r->brand;
        $product->product_type=$r->product_type?:0;

        ///////Image Uploard Start////////////
        if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',1)->where('use_Of_file',1)->where('src_id',$product->id)->first();
          if(!$media){
          $media =new Media();
          }else{
            if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
              }
          }
          $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
          $fullname = basename($file->getClientOriginalName());
          $ext =$file->getClientOriginalExtension();
          $size =$file->getSize();

          $year =carbon::now()->format('Y');
          $month =carbon::now()->format('M');
          $folder = $month.'_'.$year;

          $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
          $path ="medies/".$folder;
          $fullpath ="medies/".$folder.'/'.$img;
          $media->src_type=1;
          $media->use_Of_file=1;
          $media->src_id=$product->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();

        }

        ///////Image Uploard End////////////

        ///////Gallery Uploard End////////////

        $files=$r->file('gallery_image');
        if($files){

            foreach($files as $file)
            {

                  $media =new Media();
                  $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                  $fullname = basename($file->getClientOriginalName());
                  $ext =$file->getClientOriginalExtension();
                  $size =$file->getSize();
        
                  $year =carbon::now()->format('Y');
                  $month =carbon::now()->format('M');
                  $folder = $month.'_'.$year;
        
                  $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
                  $path ="medies/".$folder;
                  $fullpath ="medies/".$folder.'/'.$img;
                  $media->src_type=1;
                  $media->use_Of_file=3;
                  $media->src_id=$product->id;
                  $media->file_name=Str::limit($fullname,250);
                  $media->alt_text=Str::limit($name,250);
                  $media->file_size=$size;
                  $media->file_type=1;
                  $file->move(public_path($path), $img);
                  $media->file_url =$fullpath;
                  $media->addedby_id=auth::id();
                  $media->save(); 
                
            }
        }

        ///////Gallery Uploard End////////////

        $slug =Str::slug($r->name);
        if($slug==null){
        $product->slug=$product->id;
        }else{
        if(Post::where('type',2)->where('slug',$slug)->whereNotIn('id',[$product->id])->count() >0){
        $product->slug=$slug.'-'.$product->id;
        }else{
        $product->slug=$slug;
        }
        }
        if($r->created_at){
          $product->created_at =$r->created_at;
        }
        $product->status =$r->status?'active':'inactive';
        $product->fetured =$r->fetured?1:0;
        $product->editedby_id =Auth::id();
        $product->save();

      //Category posts
      if($r->categoryid){

      $product->productCtgs()->whereNotIn('reff_id',$r->categoryid)->delete();

       for ($i=0; $i < count($r->categoryid); $i++) {

        $ctg = $product->productCtgs()->where('reff_id',$r->categoryid[$i])->first();

        if($ctg){}else{
        $ctg =new PostAttribute();
        $ctg->src_id=$product->id;
        $ctg->reff_id=$r->categoryid[$i];
        $ctg->type=0;
        }
        $ctg->drag=$i;
        $ctg->save();
       }

     }else{
        $product->productCtgs()->delete();
       }


       //Tags posts
      if($r->tags){

      $product->productTags()->whereNotIn('reff_id',$r->tags)->delete();

       for ($i=0; $i < count($r->tags); $i++) {

        $tag = $product->productTags()->where('reff_id',$r->tags[$i])->first();

        if($tag){}else{
        $tag =new PostAttribute();
        $tag->src_id=$product->id;
        $tag->reff_id=$r->tags[$i];
        $tag->type=4;
        }
        $tag->drag=$i;
        $tag->save();
       }

     }else{
        $product->productTags()->delete();
       }
       
      //Attribute Serialize Date
      if($r->attributeSerial){
        for ($i=0; $i < count($r->attributeSerial); $i++) {
          $data = $product->productAttibutes()->find($r->attributeSerial[$i]);
          if($data){
            $data->drag=$i;
            $data->save();
          }
        }
      }

      if($r->extraAttributeSerial){
        for ($i=0; $i < count($r->extraAttributeSerial); $i++) {
          $data = $product->extraAttribute()->find($r->extraAttributeSerial[$i]);
          if($data){
            $data->drag=$i;
            $data->save();
          }
        }
      }
      //Attribute Serialize Date

       ///////Search Key Start////////
        $key='';
        $key.=$product->name;
        //Post Category Name
        foreach($product->productCtgs as $postctg){
            if($ctg=Attribute::where('type',0)->find($postctg->catagory_id)){
                $key.=' '.$ctg->name;
            }
        }
        $product->search_key=Str::limit($key,450);
        
        $product->save();
        ///////Search Key End////////

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

    }


    public function productsUpdateAjax(Request $r,$column,$id){

      


      $product =Post::where('type',2)->find($id);
      $attriMessage='';
      if($r->ajax() && $product){

        //Product Attribute Filters
        if($column=='attributesItemFilter'){
          $attri =Attribute::where('type',9)->where('parent_id',null)->find($r->attriID);
          if($attri){
            $viewData = view('admin.products.includes.attritubeItems',compact('product','attri'))->render();
            return Response()->json([
                'success' => true,
                'viewData' => $viewData,
            ]);

          }
        }

        //Product Attribute 
        if($column=='attributesItemAdd' || $column=='attributesItemDelete' || $column=='attributesItemColor' || $column=='attributesItemImage'){


          if($column=='attributesItemAdd'){

            $attri =Attribute::where('type',9)->where('parent_id','<>',null)->find($r->attriID);
            if($attri){
              $data =PostAttribute::where('type',3)->where('src_id',$product->id)->where('parent_id',$attri->id)->first();
              if(!$data){
                $data =new PostAttribute();
                $data->src_id=$product->id; //Product ID
                $data->parent_id=$attri->id; //Attribute Items ID
                $data->reff_id=$attri->parent_id; //Main Attribute ID
                $data->type=3;
                $data->addedby_id=auth::id();
                $data->save();
              }else{
                $attriMessage='<span class="text-danger">Already Added Attribute item!</span>';
              }
            }

          }

          if($column=='attributesItemColor'){
            $data =PostAttribute::where('type',3)->where('src_id',$product->id)->find($r->attriID);

            if($data){
              $data->value_1=$r->attriValue?:null;
              $data->save();
            }
          }


          if($column=='attributesItemImage'){
            $data =PostAttribute::where('type',3)->where('src_id',$product->id)->find($r->attriID);

            if($data){
              
              ///////Image Uploard Start////////////
                if($r->hasFile('attriValue')){
                   $file=$r->attriValue;
                   $media =Media::latest()->where('src_type',8)->where('use_Of_file',1)->where('src_id',$data->id)->first();
                    if(!$media){
                    $media =new Media();
                    }else{
                      if(File::exists(public_path($media->file_url))){
                            File::delete(public_path($media->file_url));
                        }
                    }
                    $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                    $fullname = basename($file->getClientOriginalName());
                    $ext =$file->getClientOriginalExtension();
                    $size =$file->getSize();

                    $year =carbon::now()->format('Y');
                    $month =carbon::now()->format('M');
                    $folder = $month.'_'.$year;

                    $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
                    $path ="medies/".$folder;
                    $fullpath ="medies/".$folder.'/'.$img;
                    $media->src_type=8;
                    $media->use_Of_file=1;
                    $media->src_id=$data->id;
                    $media->file_name=Str::limit($fullname,250);
                    $media->alt_text=Str::limit($name,250);
                    $media->file_size=$size;
                    $media->file_type=1;
                    $file->move(public_path($path), $img);
                    $media->file_url =$fullpath;
                    $media->addedby_id=auth::id();
                    $media->save();

                }
                
                ///////Image Uploard End////////////
                
            }
          }


          if($column=='attributesItemDelete'){
            $data =PostAttribute::where('type',3)->where('src_id',$product->id)->find($r->attriID);

            if($data){
              //More Additional Work..

              if($data->imageFile){

                if(File::exists(public_path($data->imageFile->file_url))){
                      File::delete(public_path($data->imageFile->file_url));
                  }
              }

              $data->delete();
            }

          }


          $viewData = view('admin.products.includes.attributeItemsList',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }
        //Product Attribute End


        //Product Variations Start
        if($column=='priceVariationStatus'){
          $product->variation_status=$product->variation_status?false:true;
          $product->save();

          $viewData = view('admin.products.includes.productVariation',compact('product','attriMessage'))->render();

          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }

        if($column=='variationItemsAdd'){

          $hasSelection =false;
          if($r->variationItems){
            foreach($r->variationItems as $vi){
              if($vi!==null && $vi!==''){
                $hasSelection =true;
              }
            }
          }

          if($hasSelection){

            $uniIDS =$product->id;
            for ($i=0; $i < count($r->variationItems); $i++) {
              $uniIDS.=$r->variationItems[$i];
            }

            $checkSku =PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$uniIDS)->get();

            if($checkSku->count() > 0){
              $attriMessage ='<span class="text-danger">Already Added Items!!</span>';

            }else{

                //Sku Items
                for ($i=0; $i < count($r->variationItems); $i++) {
                  if($r->variationItems[$i]!=null){

                      $attri =Attribute::where('type',9)->where('parent_id','<>',null)->find($r->variationItems[$i]);
                      if($attri){
                        $data =new PostAttribute();
                        $data->src_id=$product->id; //Product ID
                        $data->parent_id=$attri->id; //Attribute Items ID
                        $data->reff_id=$attri->parent_id; //Main Attribute ID
                        $data->sku_id=$uniIDS; //Sku  ID
                        $data->type=4;
                        $data->addedby_id=auth::id();
                        $data->save();
                      }
                  }
                }

            }

          }else{
            $attriMessage ='<span class="text-danger">Please Select At Least One Variation Item (Color, Size etc.) Before Adding!!</span>';
          }

          $viewData = view('admin.products.includes.productVariation',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
              'countdd' => $r->variationItems?count($r->variationItems):0,
          ]);

        }

        if($column=='variationItemsEdit'){

          $oldSkuId =$r->skuID;
          $rows =PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$oldSkuId)->get();

          $hasSelection =false;
          if($r->variationItems){
            foreach($r->variationItems as $vi){
              if($vi!==null && $vi!==''){
                $hasSelection =true;
              }
            }
          }

          if(!$hasSelection){
            $attriMessage ='<span class="text-danger">At Least One Variation Item Required!!</span>';
          }

          if($rows->count() > 0 && $hasSelection){

            $newUniIDS =$product->id;
            for ($i=0; $i < count($r->variationItems); $i++) {
              $newUniIDS.=$r->variationItems[$i];
            }

            $duplicate =PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$newUniIDS)->where('sku_id','<>',$oldSkuId)->exists();

            if($newUniIDS!=$oldSkuId && $duplicate){
              $attriMessage ='<span class="text-danger">This Combination Already Exists!!</span>';
            }else{

              $primaryRow =$rows->sortBy('id')->first();
              $keptRowIds =[];

              for ($i=0; $i < count($r->variationItems); $i++) {
                if($r->variationItems[$i]!=null){

                    $attri =Attribute::where('type',9)->where('parent_id','<>',null)->find($r->variationItems[$i]);
                    if($attri){
                      $row =$rows->firstWhere('reff_id',$attri->parent_id);
                      if(!$row){
                        //New group item (e.g. Size added to a Color only SKU)
                        $row =new PostAttribute();
                        $row->src_id=$product->id;
                        $row->reff_id=$attri->parent_id;
                        $row->type=4;
                        $row->value_1=$primaryRow->value_1;
                        $row->duration=$primaryRow->duration;
                        $row->addedby_id=auth::id();
                      }
                      $row->parent_id =$attri->id;
                      $row->sku_id =$newUniIDS;
                      $row->save();
                      $keptRowIds[] =$row->id;
                    }
                }
              }

              //Remove cleared group items, keep SKU image on a remaining row
              $removeRows =$rows->whereNotIn('id',$keptRowIds);
              $imageKeeper =PostAttribute::whereIn('id',$keptRowIds)->orderBy('id')->first();
              foreach($removeRows as $removeRow){
                if($removeRow->skuImageFile && $imageKeeper && !$imageKeeper->skuImageFile){
                  $removeRow->skuImageFile->src_id =$imageKeeper->id;
                  $removeRow->skuImageFile->save();
                }elseif($removeRow->skuImageFile){
                  if(File::exists(public_path($removeRow->skuImageFile->file_url))){
                      File::delete(public_path($removeRow->skuImageFile->file_url));
                  }
                  $removeRow->skuImageFile->delete();
                }
                $removeRow->delete();
              }

            }

          }

          $viewData = view('admin.products.includes.productVariation',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }

        if($column=='variationItemsUpdate'){

          if($r->skuID){
            PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$r->skuID)
              ->update([
                  'value_1'=>($r->price!==null && $r->price!=='')?$r->price:null,
                  'duration'=>($r->quantity!==null && $r->quantity!=='')?$r->quantity:null,
              ]);
          }

          $viewData = view('admin.products.includes.productVariation',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }

        if($column=='variationItemsDelete'){

          if($r->skuID){
            $skuRows =PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$r->skuID)->get();
            foreach($skuRows as $skuRow){
              if($skuRow->skuImageFile){
                if(File::exists(public_path($skuRow->skuImageFile->file_url))){
                      File::delete(public_path($skuRow->skuImageFile->file_url));
                  }
                $skuRow->skuImageFile->delete();
              }
              $skuRow->delete();
            }
          }

          $viewData = view('admin.products.includes.productVariation',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }

        if($column=='variationItemsImage'){

          if($r->skuID && $r->hasFile('image')){

            $primaryRow =PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$r->skuID)->first();

            if($primaryRow){

                $file=$r->image;
                $media =Media::latest()->where('src_type',10)->where('use_Of_file',1)->where('src_id',$primaryRow->id)->first();
                if(!$media){
                $media =new Media();
                }else{
                  if(File::exists(public_path($media->file_url))){
                        File::delete(public_path($media->file_url));
                    }
                }
                $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                $fullname = basename($file->getClientOriginalName());
                $ext =$file->getClientOriginalExtension();
                $size =$file->getSize();

                $year =carbon::now()->format('Y');
                $month =carbon::now()->format('M');
                $folder = $month.'_'.$year;

                $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
                $path ="medies/".$folder;
                $fullpath ="medies/".$folder.'/'.$img;
                $media->src_type=10;
                $media->use_Of_file=1;
                $media->src_id=$primaryRow->id;
                $media->file_name=Str::limit($fullname,250);
                $media->alt_text=Str::limit($name,250);
                $media->file_size=$size;
                $media->file_type=1;
                $file->move(public_path($path), $img);
                $media->file_url =$fullpath;
                $media->addedby_id=auth::id();
                $media->save();

            }

          }

          $viewData = view('admin.products.includes.productVariation',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }

        //Product Variations End


        //Extra Product Attribute
        if($column=='extraAttributeAdd' || $column=='extraAttributeDelete'){

          //Extra Product Attribute Add
          if($column=='extraAttributeAdd'){
            $extraAttribue =new PostExtra();
            $extraAttribue->src_id=$product->id;
            $extraAttribue->type=2;
            $extraAttribue->name=Str::limit($r->title,150);
            $extraAttribue->content=Str::limit($r->value,150);
            $extraAttribue->save(); 
          }

          //Extra Product Attribute Delete
          if($column=='extraAttributeDelete'){
              $extraAttri =PostExtra::where('type',2)->find($r->attriID);
              if($extraAttri){
                $extraAttri->delete();
              }
          }
          

          $viewData = view('admin.products.includes.extraAttributeList',compact('product'))->render();

          return Response()->json([
                'success' => true,
                'viewData' => $viewData,
            ]);

        }


        if($column=='discount'){
          $product->discount=$r->data?:0;
          $product->save();
        }
        
        if($column=='pos_price'){
          $product->pos_price=$r->data?:0;
          $product->save();
        }
        
        

        if($column=='discount_type'){
          $product->discount_type=$r->data?:null;
          $product->save();
        }

        if($column=='regular_price'){
          $product->regular_price=$r->data?:0;
          $product->save();
        }

        if($column=='discount' || $column=='discount_type' || $column=='regular_price'){

          if($product->discount_type=='flat' && $product->discount < $product->regular_price){
            $product->final_price =$product->regular_price - $product->discount;
          }elseif($product->discount_type=='percent' &&  $product->discount < 100 || $product->regular_price > 0){
            $product->final_price =$product->regular_price - ($product->regular_price * $product->discount/100);
          }else{
            $product->final_price=$product->regular_price;
          }

          $product->save();

        }


        if($column=='purchase_price'){
          $product->purchase_price=$r->data?:0;
          $product->save();
        }

        if($column=='quantity'){
          $product->quantity=$r->data?:null;
          $product->save();
        }

        if($column=='stock_out_limit'){
          $product->stock_out_limit=$r->data?:0;
          $product->save();
        }

        if($column=='sku_code'){
          $product->sku_code=$r->data?:null;
          $product->save();
        }
        
        if($column=='stock_status'){
          $product->stock_status=$r->data==0?0:1;
          $product->save();
        }

        if($column=='bar_code'){
          $product->bar_code=$r->data?:null;
          $product->save();
        }

        if($column=='offer_start_date'){
          $product->offer_start_date=$r->data?:null;
          $product->save();
        }

        if($column=='offer_end_date'){
          $product->offer_end_date=$r->data?:null;
          $product->save();
        }

        if($column=='min_order_quantity'){
          $product->min_order_quantity=$r->data?:1;
          $product->save();
        }

        if($column=='max_order_quantity'){
          $product->max_order_quantity=$r->data?:null;
          $product->save();
        }

        if($column=='weight_unit'){
          $product->weight_unit=$r->data?Str::limit($r->data,100):null;
          $product->save();
        }
        if($column=='weight_amount'){
          $product->weight_amount=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }
        if($column=='dimensions_unit'){
          $product->dimensions_unit=$r->data?Str::limit($r->data,100):null;
          $product->save();
        }

        if($column=='dimensions_length'){
          $product->dimensions_length=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }

        if($column=='dimensions_width'){
          $product->dimensions_width=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }

        if($column=='dimensions_height'){
          $product->dimensions_height=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }


        return Response()->json([
                'success' => true,
            ]);

      }
      

    }


    public function productsSingleView(Request $r,$id){

      return $r;
    }

    public function productsView(Request $r,$id){

      $product =Post::where('type',2)->find($id);
      if(!$product){
        Session()->flash('error','This Products Are Not Found');
        return redirect()->route('admin.products');
      }
      

      return view('admin.products.productsView',compact('product'));
    }


    public function productsDelete($id){
        
      $product =Post::where('type',2)->find($id);
      if(!$product){
        Session()->flash('error','This Products Are Not Found');
        return redirect()->route('admin.products');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['products']['all']);
      if($allPer && $product->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.products');
      }

      $medias =Media::latest()->where('src_type',1)->where('src_id',$product->id)->get();
        foreach($medias as $media){
          if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
          }
          $media->delete();
        }

      $product->productCtgs()->delete();
      $product->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

  
  // Products Management Function End


  //Products Category Function
  public function productsCategories(Request $r){

    $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
    // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::where('type',0)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

          if($allPer && $data->addedby_id!=Auth::id()){
            // You are unauthorized Try!!
          }else{

            if($r->action==1){
              $data->status='active';
              $data->save();
            }elseif($r->action==2){
              $data->status='inactive';
              $data->save();
            }elseif($r->action==3){
              $data->fetured=true;
              $data->save();
            }elseif($r->action==4){
              $data->fetured=false;
              $data->save();
            }elseif($r->action==5){
              
              $medias =Media::latest()->where('src_type',3)->where('src_id',$data->id)->get();
              foreach($medias as $media){
                if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
                }
                $media->delete();
              }

              //Post Category sub Category replace
              foreach($data->subctgs as $subctg){
                $subctg->parent_id=$data->parent_id;
                $subctg->save();
              }
              
              $data->delete();
            }

          }


        }

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

    //Filter Action End

    $categories =Attribute::latest()->where('type',0)
    ->where(function($q) use ($r) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }

          if($r->status){
             $q->where('status',$r->status); 
          }

    })
    ->select(['id','name','slug','parent_id','view','type','created_at','addedby_id','status','fetured'])
        ->paginate(25)->appends([
          'search'=>$r->search,
          'status'=>$r->status,
        ]);

    //Total Count Results
    $totals = DB::table('attributes')
    ->where('type',0)
    ->selectRaw('count(*) as total')
    ->selectRaw("count(case when status = 'active' then 1 end) as active")
    ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
    ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
    ->first();
      
    return view('admin.products.category.categoriesAll',compact('categories','totals','r'));

    }

    public function productsCategoriesCreate(){

      $category =new Attribute();
      $category->type =0;
      $category->status ='temp';
      $category->addedby_id =Auth::id();
      $category->save();

      return redirect()->route('admin.productsCategoriesEdit',$category->id);

    }

    public function productsCategoriesEdit($id){
      $category =Attribute::where('type',0)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.productsCategories');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
      if($allPer && $category->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.productsCategories');
      }

      $parents =Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->get();

      return view('admin.products.category.categoryEdit',compact('category','parents'));
    }

    public function productsCategoriesUpdate(Request $r,$id){
      $category =Attribute::where('type',0)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.productsCategories');
      }
        
         
    
       $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'old_id' => 'nullable|numeric',
            'seo_desc' => 'nullable|max:200',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
 
        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }

        $category->name=$r->name;
        $category->old_id=$r->old_id;
        $category->description=$r->description;
        $category->seo_title=$r->seo_title;
        $category->seo_description=$r->seo_description;
        $category->seo_keyword=$r->seo_keyword;
        if($r->parent_id==$category->parent_id){}else{
          $category->parent_id=$r->parent_id;
         }

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$category->id)->first();
          if(!$media){
          $media =new Media();
          }else{
            if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
              }
          }
          $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
          $fullname = basename($file->getClientOriginalName());
          $ext =$file->getClientOriginalExtension();
          $size =$file->getSize();

          $year =carbon::now()->format('Y');
          $month =carbon::now()->format('M');
          $folder = $month.'_'.$year;

          $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
          $path ="medies/".$folder;
          $fullpath ="medies/".$folder.'/'.$img;
          $media->src_type=3;
          $media->use_Of_file=1;
          $media->src_id=$category->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();

      }
      
      ///////Image Uploard End////////////

      ///////Banner Uploard End////////////

        if($r->hasFile('banner')){
         $file=$r->banner;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',2)->where('src_id',$category->id)->first();

          if(!$media){
          $media =new Media();
          }else{
            if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
              }
          }
          $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
          $fullname = basename($file->getClientOriginalName());
          $ext =$file->getClientOriginalExtension();
          $size =$file->getSize();

          $year =carbon::now()->format('Y');
          $month =carbon::now()->format('M');
          $folder = $month.'_'.$year;

          $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
          $path ="medies/".$folder;
          $fullpath ="medies/".$folder.'/'.$img;
          $media->src_type=3;
          $media->use_Of_file=2;
          $media->src_id=$category->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();

      }

      ///////Banner Uploard End////////////


      $slug =Str::slug($r->name);
       if($slug==null){
        $category->slug=$category->id;
       }else{
        if(Attribute::where('type',0)->where('slug',$slug)->whereNotIn('id',[$category->id])->count() >0){
        $category->slug=$slug.'-'.$category->id;
        }else{
        $category->slug=$slug;
        }
       }
      $category->status =$r->status?'active':'inactive';
      $category->fetured =$r->fetured?1:0;
      $category->editedby_id =Auth::id();
      $category->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function productsCategoriesDelete($id){

      $category =Attribute::where('type',0)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.productsCategories');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
      if($allPer && $category->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.productsCategories');
      }

      //Category Media File Delete
      $medias =Media::latest()->where('src_type',3)->where('src_id',$category->id)->get();
        foreach($medias as $media){
          if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
          }
          $media->delete();
        }

        //Product Category sub Category replace
        foreach($category->subctgs as $subctg){
          $subctg->parent_id=$category->parent_id;
          $subctg->save();
        }
        
        $category->delete();

       Session()->flash('success','Your Are Successfully Done');
       return redirect()->back();

    }

    //Product Category Function End

    //Product Tags Function

    public function productsTags(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::where('type',10)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

          if($allPer && $data->addedby_id!=Auth::id()){
            // You are unauthorized Try!!
          }else{

            if($r->action==1){
              $data->status='active';
              $data->save();
            }elseif($r->action==2){
              $data->status='inactive';
              $data->save();
            }elseif($r->action==5){
              $data->delete();
            }

          }


        }

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

    //Filter Action End

      $tags =Attribute::latest()->where('type',10)
      ->where(function($q) use($r){

        if($r->search){
          $q->where('name','like','%'.$r->search.'%');
        }
        
      })
      ->paginate(25);

      //Total Count Results
    $totals = DB::table('attributes')
    ->where('type',10)
    ->selectRaw('count(*) as total')
    ->selectRaw("count(case when status = 'active' then 1 end) as active")
    ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
    ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
    ->first();

      return view('admin.products.tags.tagsAll',compact('tags','totals','r'));
    }

    public function productsTagsCreate(){

      $tag =new Attribute();
      $tag->type =10;
      $tag->status ='temp';
      $tag->addedby_id =Auth::id();
      $tag->save();

      return redirect()->route('admin.productsTagsEdit',$tag->id);

    }

    public function productsTagsEdit($id){

      $tag =Attribute::where('type',10)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.productsTags');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
      if($allPer && $tag->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.productsTags');
      }

      return view('admin.products.tags.tagEdit',compact('tag'));

    }

    public function productsTagsUpdate(Request $r,$id){

      $tag =Attribute::where('type',10)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.productsTags');
      }

      $check = $r->validate([
          'name' => 'required|max:191',
      ]);

      $tag->name=$r->name;
      $tag->description=$r->description;
      $tag->status =$r->status?'active':'inactive';
      $tag->addedby_id =Auth::id();
      $tag->save();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function productsTagsDelete($id){

      $tag =Attribute::where('type',10)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.productsTags');
      }

      $tag->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();


    }
    


    //Product Tags Function End

    //Product Attributes Function 
    public function productsAttributes(Request $r){
      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::latest()->where('type',9)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

            if($r->action==1){
              $data->status='active';
              $data->save();
            }elseif($r->action==2){
              $data->status='inactive';
              $data->save();
            }elseif($r->action==5){
              
              foreach($data->subAttributes as $item){

                $medias =Media::latest()->where('src_type',3)->where('src_id',$item->id)->get();
                foreach($medias as $media){
                  if(File::exists(public_path($media->file_url))){
                    File::delete(public_path($media->file_url));
                  }
                  $media->delete();
                }

                $item->delete();

              }

              $data->delete();
            }

        }

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

      //Filter Action End

      $attributes=Attribute::latest()->where('type',9)->where('status','<>','temp')->where('parent_id',null)
        ->where(function($q) use ($r) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }

      })
      ->paginate(25)->appends([
        'search'=>$r->search,
      ]);


      return view('admin.products.attributes.attributesAll',compact('attributes','r'));
    }

    public function productsAttributesCreate(){
      $attribute =Attribute::latest()->where('type',9)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$attribute){
      $attribute =new Attribute();
      $attribute->type =9;
      $attribute->status ='temp';
      $attribute->addedby_id =Auth::id();
      $attribute->save();
      }else{
      $attribute->parent_id =null;
      $attribute->created_at =Carbon::now();
      $attribute->save();
      }

      return redirect()->route('admin.productsAttributesEdit',$attribute->id);
    }

    public function productsAttributesEdit(Request $r,$id){

      if($r->action){
        if($r->checkid){

        $datas=Attribute::latest()->where('type',9)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

            if($r->action==5){
              
              $medias =Media::latest()->where('src_type',3)->where('src_id',$data->id)->get();
              foreach($medias as $media){
                if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
                }
                $media->delete();
              }

              $data->delete();
            }

        }

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }


      $attribute =Attribute::where('type',9)->where('parent_id',null)->find($id);

      if(!$attribute){
        Session()->flash('error','This Attribute Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

      $items=Attribute::latest()->where('type',9)->where('status','<>','temp')->where('parent_id',$attribute->id)
        ->where(function($q) use ($r) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }

      })
      ->paginate(50)->appends([
        'search'=>$r->search,
      ]);

      return view('admin.products.attributes.attributesEdit',compact('attribute','r','items'));
    }

    public function productsAttributesUpdate(Request $r,$id){
      $attribute =Attribute::where('type',9)->find($id);
      if(!$attribute){
        Session()->flash('error','This Attribute Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

       $check = $r->validate([
            'name' => 'required|max:191',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }
        //View =1=text,2=color,3=image

        $attribute->name=$r->name;
        $attribute->description=$r->description;
        $attribute->view=$r->type;
        $slug =Str::slug($r->name);
       if($slug==null){
        $attribute->slug=$attribute->id;
       }else{
        if(Attribute::where('type',9)->where('slug',$slug)->whereNotIn('id',[$attribute->id])->count() >0){
        $attribute->slug=$slug.'-'.$attribute->id;
        }else{
        $attribute->slug=$slug;
        }
       }
      $attribute->status =$r->status?'active':'inactive';
      $attribute->fetured =$r->fetured?1:0;
      $attribute->editedby_id =Auth::id();
      $attribute->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }


    public function productsAttributesDelete($id){

      $attribute =Attribute::where('type',9)->where('parent_id',null)->find($id);
      if(!$attribute){
        Session()->flash('error','This Attribute Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

      $attribute->subAttributes()->delete();


      $attribute->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    public function productsAttributesItemAdd($id){
      $Rattribute =Attribute::where('type',9)->find($id);
      if(!$Rattribute){
        Session()->flash('error','This Attribute Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

      $attribute =Attribute::latest()->where('type',9)->where('parent_id',$Rattribute->id)->where('addedby_id',Auth::id())->where('status','temp')->first();

      if(!$attribute){
      $attribute =new Attribute();
      $attribute->type =9;
      $attribute->parent_id =$Rattribute->id;
      $attribute->status ='temp';
      $attribute->addedby_id =Auth::id();
      $attribute->save();
      }else{
      $attribute->created_at =Carbon::now();
      $attribute->save();
      }
      return redirect()->route('admin.productsAttributesItemEdit',$attribute->id);


    }

    public function productsAttributesItemEdit(Request $r,$id){
      $attribute =Attribute::where('type',9)->find($id);
      if(!$attribute){
        Session()->flash('error','This Attribute Item Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

      return view('admin.products.attributes.attributesItemEdit',compact('attribute'));

    }

    public function productsAttributesItemUpdate(Request $r,$id){
      $attribute =Attribute::where('type',9)->find($id);
      if(!$attribute){
        Session()->flash('error','This Attribute Item Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

      $check = $r->validate([
          'name' => 'required|max:191',
          'color' => 'nullable|max:191',
          //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      if(!$check){
          Session::flash('error','Need To validatation');
          return back();
      }
      //View =1=text,2=color,3=image

      $attribute->name=$r->name;
      $attribute->description=$r->description;
      $attribute->icon=$r->color;

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$attribute->id)->first();
          if(!$media){
          $media =new Media();
          }else{
            if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
              }
          }
          $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
          $fullname = basename($file->getClientOriginalName());
          $ext =$file->getClientOriginalExtension();
          $size =$file->getSize();

          $year =carbon::now()->format('Y');
          $month =carbon::now()->format('M');
          $folder = $month.'_'.$year;

          $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
          $path ="medies/".$folder;
          $fullpath ="medies/".$folder.'/'.$img;
          $media->src_type=3;
          $media->use_Of_file=1;
          $media->src_id=$attribute->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();

      }
      
      ///////Image Uploard End////////////


      $slug =Str::slug($r->name);
       if($slug==null){
        $attribute->slug=$attribute->id;
       }else{
        if(Attribute::where('type',9)->where('slug',$slug)->whereNotIn('id',[$attribute->id])->count() >0){
        $attribute->slug=$slug.'-'.$attribute->id;
        }else{
        $attribute->slug=$slug;
        }
       }
      $attribute->status ='active';
      $attribute->editedby_id =Auth::id();
      $attribute->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();


    }

    //Service Attributes Function End


    //Stock list Function End


    public function stockList(Request $r){
        //return $r;
    
      $products =Post::latest()->where('type',2)->where('status','<>','temp')
        ->where(function($q) use ($r) {

            if($r->search){
                $q->where('search_key','LIKE','%'.$r->search.'%');
            }
            
            if($r->category)
            {
                $q->whereHas('productCtgs',function($qq) use($r){
                    $qq->where('reff_id',$r->category);
                });
            }

            if($r->price){
             $q->where('final_price','<=',$r->price); 
            }
            
            if($r->quantity){
             $q->where('quantity','<=',$r->quantity); 
            }


        })
        //->select(['id','name','final_price','slug','view','type','brand_id','created_at','addedby_id','status','fetured'])
        ->paginate(25)->appends([
          'search'=>$r->search,
          'category'=>$r->category,
          'price'=>$r->price,
          'quantity'=>$r->quantity,
        ]);
    
    $categories =Attribute::latest()->where('type',0)->where('status','<>','temp')->select(['id','name','parent_id'])->get(25);
    
      return view('admin.stocks.stockList',compact('products','r','categories'));
    }

    
    //Stock list Function End



    public function ecommerceSetting($type){

      if($type=='general' || $type=='shipping' || $type=='payments'){

        $shippingZones =PostExtra::where('type',3)->where('status','<>','temp')->where('parent_id',null)->get();
        
        return view('admin.ecommerce-setting.settings',compact('type','shippingZones'));
      }else{

        Session()->flash('error','Unknown Type Action Not Allow');
        return redirect()->route('admin.ecommerceSetting',['type'=>'general']);
      }
      
    }

    public function ecommerceSettingCreate($type){

      if($type=='shipping'){
        
        $zone =PostExtra::where('type',3)->where('status','temp')->where('parent_id',null)->where('addedby_id',Auth::id())->first();
        
        if(!$zone){
            $zone =new PostExtra();
            $zone->type=3;
            $zone->status='temp';
            $zone->parent_id=null;
            $zone->addedby_id=Auth::id();
            $zone->save();
        }
        
        return redirect()->route('admin.ecommerceSettingEdit',[$type,$zone->id]);
        
        $zones =Country::where('type',2)->select(['id','name','bn_name','parent_id'])->orderBy('name')->get();
        return view('admin.ecommerce-setting.shippingZoneCreate',compact('zones'));

      }

    }


    public function ecommerceSettingEdit($type,$id){

      if($type=='shipping'){
        $zone =PostExtra::where('type',3)->find($id);
        
        if(!$zone){
            Session()->flash('error','Zone Are Not Found');
            return redirect()->route('admin.ecommerceSetting',$type);
        }
        $zones =Country::where('type',2)->where('parent_id',629)->select(['id','name','bn_name','parent_id'])->orderBy('name')->get();
        return view('admin.ecommerce-setting.shippingZoneCreate',compact('zones','zone','type'));

      }

      Session::flash('error','Worng Type Action Are Not Work!');
      return redirect()->back();


    }
    
    public function ecommerceSettingPost(Request $r,$type){
        $general =general();
        if($type=='general'){
            
            $check = $r->validate([
              'frozen_amount' => 'nullable|numeric',
              'dye_amount' => 'nullable|numeric',
              'mix_amount' => 'nullable|numeric',
              'shipping_charge_type' => 'nullable|numeric',
              'defult_shipping_charge' => 'nullable|numeric',
              'inside_dhaka_shipping_charge' => 'nullable|numeric',
              'outside_dhaka_shipping_charge' => 'nullable|numeric',
              'tax' => 'nullable|numeric',
              'tax_status' => 'nullable|numeric',
              'weekend_holyday' => 'nullable|numeric',
            ]);

            $general->frozen_amount=$r->frozen_amount?:0;
            $general->dye_amount=$r->dye_amount?:0;
            $general->mix_amount=$r->mix_amount?:0;
            $general->defult_shipping_charge=$r->defult_shipping_charge?:0;
            $general->inside_dhaka_shipping_charge=$r->inside_dhaka_shipping_charge?:0;
            $general->outside_dhaka_shipping_charge=$r->outside_dhaka_shipping_charge?:0;
            $general->shipping_charge_type=$r->shipping_charge_type?:0;
            $general->tax=$r->tax?:0;
            $general->tax_status=$r->tax_status?:0;
            $general->weekend_holyday=$r->weekend_holyday?:null;
            $general->save();
            
            Session::flash('success','General Information Are Update Successfully Done!');
            return redirect()->back();
        }
        
        return 'stop';
    }

    public function ecommerceSettingUpdate(Request $r,$type,$id){
        
        if($type=='shipping'){
            
            $zone =PostExtra::where('type',3)->where('parent_id',null)->find($id);
        
            if(!$zone){
                Session()->flash('error','Zone Are Not Found');
                return redirect()->route('admin.ecommerceSetting',$type);
            }
            
            $check = $r->validate([
              'name' => 'required|max:191',
              'shipping_day' => 'required|numeric',
              'zones.*' => 'required|numeric',
              'status' => 'required',
            ]);
            
            
            $zone->name =$r->name;
            $zone->content =$r->description;
            $zone->status =$r->status;
            $zone->shipping_charge =$r->shipping_day;
            $zone->save();
            
            //Category posts
              if($r->zones){
        
              $zone->zoneLists()->whereNotIn('src_id',$r->zones)->delete();
        
               for ($i=0; $i < count($r->zones); $i++) {
        
                $list = $zone->zoneLists()->where('src_id',$r->zones[$i])->first();
        
                if($list){}else{
                $list =new PostExtra();
                $list->parent_id=$zone->id;
                $list->src_id=$r->zones[$i];
                $list->type=3;
                }
                $list->drag=$i;
                $list->save();
               }
        
             }else{
                $zone->zoneLists()->delete();
               }
            
            
            Session::flash('success','Update Successfully Done!');
            return redirect()->back();

        }
        
     
      Session::flash('error','Worng Type Action Are Not Work!');
      return redirect()->back();

    }











}
