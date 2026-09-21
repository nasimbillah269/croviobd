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
use Artisan;
use Validator;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\PostExtra;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\General;
use App\Models\Country;
use App\Models\Media;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use GuzzleHttp\Client;
use App\Mail\RegistrationMail;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AdminController extends Controller
{

    public function dashboard(){
        
        
        ///Reports  Summery Dashboard
        
        
        //$hash ='$2y$10$LickUZ7b6rNaRfhDUsJjZeWUIc4nmOf/mt5ev15I1upDjLOWdYZUC';
        
        // $hash ='$P$BKzGZqyp2d/PTkPu.NpNdg2C3I3e100';
        
        // $password ='B@ticromJapan#';
        
        // if ( WpPassword::check($password, $hash) ) {
        //     return  'Password success!';
        // } else {
        //      return 'Password failed :(';
        // }

        // return 'stop';

        
    //   return password_hash("123456789", PASSWORD_DEFAULT);
        
    //     '25f9e794323b453885f5181f1b624d0b';
        
    //     return $user->password2;
        

        // $products =Post::where('type',2)->where('status','active')->where('quantity',null)->orderBy('id','asc')->limit(500)->get();
        
        // if($products->count() == 0){
        //     return 'Data Finish';
        // }
        
        // foreach($products as $product){
        //     $product->quantity=100;
        //     $product->save();
        // }
        
        // return $products->count();
        
        $services30Days=Post::where('type',2)
        ->where('status','<>','temp')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();

        $posts30Days=Post::where('type',1)
        ->where('status','<>','temp')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();

        $pagesTotal=Post::where('type',0)
        ->where('status','<>','temp')
        ->count();

        $brandTotal=Attribute::where('type',2)
        ->where('status','<>','temp')
        ->count();


        $reports=array(
                    "services"=>$services30Days,
                    "posts"=>$posts30Days,
                    "pages"=>$pagesTotal,
                    "brands"=>$brandTotal,
                );
        ///Reports  Summery Dashboard

        $posts =Post::latest()->where('type',1)->where('status','<>','temp')->paginate(10);
        $products =Post::latest()->where('type',2)->where('status','<>','temp')->paginate(10);
        
        
        
        //return User::where('old_id','2475')->get();
        
        return view('admin.dashboard',compact('posts','reports','products'));
      
    }

    
    public function dashboardDataReaction (){
        
        return view('admin.dataaction');
    }

    public function mailSend(){

      $r='md name';
      Mail::to('rabiulk449@gmail.com')->send(new RegistrationMail($r));
      Session()->flash('mailsend','Mail Send Success. We are response as soon as possible.');
      return back();

    }


  public function myProfile(Request $r){

    $user =Auth::user();

    return view('admin.users.myProfile',compact('user'));
    
  }


  public function myProfileUpdate(Request $r){

    $user =Auth::user();

     $check = $r->validate([
          'name' => 'required|max:100|unique:users,name,'.$user->id,
          'email' => 'required|max:100|unique:users,email,'.$user->id,
          'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
          'gender' => 'nullable|max:10',
          'address' => 'nullable|max:191',
          'prefecture' => 'nullable|numeric',
          'city' => 'nullable|max:191',
          'postal_code' => 'nullable|max:20',
          //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      $user->name =$r->name;
      $user->mobile =$r->mobile;
      $user->email =$r->email;
      $user->gender =$r->gender;
      $user->address_line1 =$r->address;
      $user->district =$r->prefecture;
      $user->city_name =$r->city;
      $user->postal_code =$r->postal_code;

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$user->id)->first();
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
          $media->src_type=6;
          $media->use_Of_file=1;
          $media->src_id=$user->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();
      }

      ///////Banner Uploard Start////////////
      if($r->hasFile('banner')){
         $file=$r->banner;
         $media =Media::latest()->where('src_type',6)->where('use_Of_file',2)->where('src_id',$user->id)->first();
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
          $media->src_type=6;
          $media->use_Of_file=2;
          $media->src_id=$user->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();
      }

      
      $user->save();

      Session()->flash('success','Your Updated Are Successfully Done!');

      return redirect()->route('admin.myProfile');

  }

  public function myProfileChangePassword(Request $r){
    $user =Auth::user();

      $check = $r->validate([
          'old_password' => 'required|string|min:8',
          'password' => 'required|string|min:8|confirmed|different:old_password',
      ]);


      if(Hash::check($r->old_password, $user->password)){
        $user->password_show=$r->password;
        $user->password=Hash::make($r->password);
        $user->update();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->route('admin.myProfile');
      }else{
        Session()->flash('error','Carrent Password Are Not Match');
        return redirect()->route('admin.myProfile');
      }

  }


  //Medias Library Route
    public function medies(Request $r){
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['medies']['all']);

      $medies =Media::latest()->where('src_type',0)
      ->where(function($q) use ($r,$allPer) {

        // Check Permission
        if($allPer){
          $q->where('addedby_id',auth::id()); 
        }

      })
      ->select(['id','file_url','file_type'])
      ->paginate(50);

      if($r->ajax())
        {
  
            return Response()->json([
                'success' => true,
                'view' => View('admin.medies.includes.mediesAll',[
                    'medies'=>$medies
                ])->render()
            ]);
        }

      return view('admin.medies.medies',compact('medies'));
    }

    public function mediesCreate(Request $r){

        $check = $r->validate([
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf,docx,zip,rar,mp4,webm,mov,wmv,mp3|max:25600',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }   

      $files=$r->file('images');
        if($files){
            foreach($files as $file){
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

                $data =new Media();
                $data->file_name=Str::limit($fullname,250);
                $data->alt_text=Str::limit($name,250);
                $data->file_size=$size;

                if($ext=='png' || $ext=='jpeg' || $ext=='svg' || $ext=='gif' || $ext=='jpg' || $ext=='webp'){
                $data->file_type=1;
                }elseif($ext=='pdf'){
                $data->file_type=2;
                }elseif($ext=='docx'){
                $data->file_type=3;
                }elseif($ext=='zip' || $ext=='rar'){
                $data->file_type=4;
                }elseif($ext=='mp4' || $ext=='webm' || $ext=='mov' || $ext=='wmv'){
                $data->file_type=5;
                }elseif($ext=='mp3'){
                $data->file_type=6;
                }
                $file->move(public_path($path), $img);
                $data->file_url =$fullpath;
                $data->addedby_id=auth::id();
                $data->save();
            }
        }

      Session()->flash('success','Your Are Successfully Done');
       return redirect()->back();
       
    }

    public function mediesEdit(Request $request, $id){
      $media =Media::find($id);
      if(!$media){
        Session()->flash('error','This File Are Not Found');
        return redirect()->back();
      }

      if($media->src_type==0){
        //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['medies']['all']);
        if($allPer && $media->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.medies');
        }
      }

      return view('admin.medies.mediaImageEdit',compact('media'));
    }

    public function mediesUpdate(Request $r, $id){
       $media =Media::find($id);
      if(!$media){
      Session()->flash('error','This File Are Not Found');
       return redirect()->route('admin.medies');
      }
       
       $media->alt_text=$r->alt_text;
       $media->caption=$r->caption;
       $media->description=$r->description;
       $media->editedby_id=auth::id();
       $media->save();
       Session()->flash('success','Your Are Successfully Done');
       return redirect()->back();
    }


    public function mediesDelete(Request $request,$id){

       if($request->ajax())
      {
     
      $media =Media::find($id);
      if(!$media){
        Session()->flash('error','This File Are Not Found');
       return Response()->json([
                'success' => false
            ]);
       }
       if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
        }
      $media->delete();
        return Response()->json([
                'success' => true
            ]);
      }      

    }

    public function mediesDeleteAll(Request $request){
      
        $check = $request->validate([
            'mediaid.*' => 'required|numeric',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
          return back();
        }

        for ($i=0; $i < count($request->mediaid); $i++) { 
          $media =Media::find($request->mediaid[$i]);
          if($media){

            //Check Authorized User
            $allPer = empty(json_decode(Auth::user()->permission->permission, true)['medies']['all']);

            if($allPer && $media->addedby_id!=Auth::id()){
              //You are unauthorized Try!!;
            }else{

              if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
              }
              $media->delete();

            }

          }

        }

        Session()->flash('success','Your Are Successfully Done');
       return redirect()->back();
    }

    //Medias Library Route End


  // Page Management Function Start
    
     public function pages(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['pages']['all']);
        // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Post::latest()->where('type',0)->whereIn('id',$r->checkid)->get();

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

      $pages=Post::latest()->where('type',0)
      ->where(function($q) use ($r,$allPer) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
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
      ->select(['id','name','slug','view','type','created_at','addedby_id','status','fetured'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'status'=>$r->status,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

      //Total Count Results
      $totals = DB::table('posts')
      ->where('type',0)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
      ->first();

      return view('admin.pages.pagesAll',compact('pages','r','totals'));

    }

    public function pagesCreate(){

      $page =new Post();
      $page->type =0;
      $page->status ='temp';
      $page->addedby_id =Auth::id();
      $page->save();
      
      return redirect()->route('admin.pagesEdit',$page->id);

    }

    public function pagesEdit($id){

      $page =Post::find($id);
      if(!$page){
        Session()->flash('error','This Page Are Not Found');
        return redirect()->route('admin.pages');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['pages']['all']);
      if($allPer && $page->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.pages');
      }

      $extraDatas=PostExtra::where('src_id',$id)->get();
      return view('admin.pages.pageEdit',compact('page','extraDatas'));

    }


     public function pagesUpdate(Request $r,$id){

      $page =Post::find($id);
      if(!$page){
        Session()->flash('error','This Page Are Not Found');
        return redirect()->route('admin.pages');
      }

       $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:120',
            'seo_desc' => 'nullable|max:200',
            'seo_keyword' => 'nullable|max:300',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }
      
      $page->name=$r->name;
      $page->short_description=$r->short_description;
      $page->description=$r->description;
      $page->seo_title=$r->seo_title;
      $page->seo_desc=$r->seo_desc;
      $page->seo_keyword=$r->seo_keyword;
      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',1)->where('use_Of_file',1)->where('src_id',$page->id)->first();
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
          $media->src_id=$page->id;
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

      ///////Image Uploard End////////////

        if($r->hasFile('banner')){
         $file=$r->banner;
         $media =Media::latest()->where('src_type',1)->where('use_Of_file',2)->where('src_id',$page->id)->first();

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
          $media->use_Of_file=2;
          $media->src_id=$page->id;
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
        $page->slug=$page->id;
       }else{
        if(Post::where('type',0)->where('slug',$slug)->whereNotIn('id',[$page->id])->count() >0){
        $page->slug=$slug.'-'.$page->id;
        }else{
        $page->slug=$slug;
        }
       }

      if($r->created_at){
        $page->created_at =$r->created_at;
      }
      $page->status =$r->status?'active':'inactive';
      $page->fetured =$r->fetured?1:0;
      $page->editedby_id =Auth::id();
      $page->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }


    public function pagesDelete($id){
      
      $page =Post::find($id);
      if(!$page){
        Session()->flash('error','This Page Are Not Found');
        return redirect()->route('admin.pages');
      }

      if($page->id==8 || $page->id==9 || $page->id==18 || $page->id==20){
        Session()->flash('error','This Page Can Not Deleted');
        return redirect()->route('admin.pages');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['pages']['all']);
      if($allPer && $page->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.pages');
      }

       //Page Extra Data Delete
        PostExtra::where('type',0)->where('src_id',$page->id)->delete();

        //Page Media File Delete
        $medies =Media::where('src_type',1)->where('src_id',$page->id)->get();
        foreach ($medies as  $media) {
            if(File::exists(public_path($media->file_url))){
                File::delete(public_path($media->file_url));
            }
            $media->delete();
        }

        //Page Delete
        $page->delete();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
      
    }


  // Page Management Function End


    //Cleints Function

    public function clients(Request $r){
      // Filter Action Start
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['clients']['all']);
      if($r->action){
        if($r->checkid){

        $datas=Attribute::latest()->where('type',3)->whereIn('id',$r->checkid)->get();

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

      $clients=Attribute::latest()->where('type',3)
        ->where(function($q) use ($r,$allPer) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }

          if($r->status){
             $q->where('status',$r->status); 
          }

          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }

      })
      ->select(['id','name','slug','type','created_at','addedby_id','status','fetured'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'status'=>$r->status,
      ]);

      //Total Count Results
      $totals = DB::table('attributes')
      ->where('type',3)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
      ->first();


      return view('admin.clients.clientsAll',compact('clients','totals','r'));
    }


    public function clientsCreate(){

      $client =new Attribute();
      $client->type =3;
      $client->status ='temp';
      $client->addedby_id =Auth::id();
      $client->save();

      return redirect()->route('admin.clientsEdit',$client->id);
    }

    public function clientsEdit($id){
      $client =Attribute::where('type',3)->find($id);
      if(!$client){
        Session()->flash('error','This Client Are Not Found');
        return redirect()->route('admin.clients');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['clients']['all']);
      if($allPer && $client->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.clients');
      }

      return view('admin.clients.clientsEdit',compact('client'));
    }

    public function clientsUpdate(Request $r,$id){

      $client =Attribute::where('type',3)->find($id);
      if(!$client){
        Session()->flash('error','This Client Are Not Found');
        return redirect()->route('admin.clients');
      }

      $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:250',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }


      $client->name=$r->name;
      $client->short_description=$r->short_description;
      $client->description=$r->description;
      $client->seo_title=$r->seo_title;
      $client->short_description=$r->short_description;
      $client->seo_keyword=$r->seo_keyword;

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$client->id)->first();
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
          $media->src_id=$client->id;
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
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',2)->where('src_id',$client->id)->first();

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
          $media->src_id=$client->id;
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
        $client->slug=$client->id;
       }else{
        if(Attribute::where('type',3)->where('slug',$slug)->whereNotIn('id',[$client->id])->count() >0){
        $client->slug=$slug.'-'.$client->id;
        }else{
        $client->slug=$slug;
        }
      }
      $client->status =$r->status?'active':'inactive';
      $client->fetured =$r->fetured?1:0;
      $client->editedby_id =Auth::id();
      $client->save();


        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

    }

    public function clientsDelete($id){
      $client =Attribute::where('type',3)->find($id);
      if(!$client){
        Session()->flash('error','This Client Are Not Found');
        return redirect()->route('admin.clients');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['clients']['all']);
      if($allPer && $client->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.clients');
      }


      $medias =Media::latest()->where('src_type',3)->where('src_id',$client->id)->get();
        foreach($medias as $media){
          if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
          }
          $media->delete();
        }

        $client->delete();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

    }


    //Cleints Function End

    //Brands Function

    public function brands(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['brands']['all']);

      // Filter Action Start
      if($r->action){
        if($r->checkid){

        $datas=Attribute::latest()->where('type',2)->whereIn('id',$r->checkid)->get();

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

      $brands=Attribute::latest()->where('type',2)
        ->where(function($q) use ($r,$allPer) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }


          if($r->status){
             $q->where('status',$r->status); 
          }

          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }

      })
      ->select(['id','name','slug','type','created_at','addedby_id','status','fetured'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'status'=>$r->status,
      ]);

      //Total Count Results
      $totals = DB::table('attributes')
      ->where('type',2)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
      ->first();



      return view('admin.brands.brandsAll',compact('brands','totals','r'));
    }


    public function brandsCreate(){

      $brand =new Attribute();
      $brand->type =2;
      $brand->status ='temp';
      $brand->addedby_id =Auth::id();
      $brand->save();

      return redirect()->route('admin.brandsEdit',$brand->id);
    }

    public function brandsEdit($id){
      $brand =Attribute::where('type',2)->find($id);
      if(!$brand){
        Session()->flash('error','This Brand Are Not Found');
        return redirect()->route('admin.brands');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['brands']['all']);
      if($allPer && $brand->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.brands');
      }

      return view('admin.brands.brandsEdit',compact('brand'));
    }

    public function brandsUpdate(Request $r,$id){

      $brand =Attribute::where('type',2)->find($id);
      if(!$brand){
        Session()->flash('error','This Brands Are Not Found');
        return redirect()->route('admin.brands');
      }

      $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:250',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }


      $brand->name=$r->name;
      $brand->short_description=$r->short_description;
      $brand->description=$r->description;
      $brand->seo_title=$r->seo_title;
      $brand->short_description=$r->short_description;
      $brand->seo_keyword=$r->seo_keyword;

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$brand->id)->first();
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
          $media->src_id=$brand->id;
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
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',2)->where('src_id',$brand->id)->first();

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
          $media->src_id=$brand->id;
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
        $brand->slug=$brand->id;
       }else{
        if(Attribute::where('type',2)->where('slug',$slug)->whereNotIn('id',[$brand->id])->count() >0){
        $brand->slug=$slug.'-'.$brand->id;
        }else{
        $brand->slug=$slug;
        }
      }
      $brand->status =$r->status?'active':'inactive';
      $brand->fetured =$r->fetured?1:0;
      $brand->editedby_id =Auth::id();
      $brand->save();


        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

    }

    public function brandsDelete($id){
      $brand =Attribute::where('type',2)->find($id);
      if(!$brand){
        Session()->flash('error','This Brand Are Not Found');
        return redirect()->route('admin.brands');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['brands']['all']);
      if($allPer && $brand->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.brands');
      }

      $medias =Media::latest()->where('src_type',3)->where('src_id',$brand->id)->get();
        foreach($medias as $media){
          if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
          }
          $media->delete();
        }

        $brand->delete();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

    }

    //Brands Function End


    //Sliders Function
    public function sliders(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['sliders']['all']);

      $sliders=Attribute::latest()->where('type',1)->where('status','<>','temp')->where('parent_id',null)
      ->where(function($q) use ($allPer) {
          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }
      })
      ->select(['id','name','location','type','created_at','addedby_id','status','fetured'])
      ->paginate(25);
      return view('admin.sliders.slidersAll',compact('sliders','r'));
    }

    public function slidersCreate(){
      $slider =Attribute::latest()->where('type',1)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$slider){
      $slider =new Attribute();
      $slider->type =1;
      $slider->status ='temp';
      $slider->addedby_id =Auth::id();
      $slider->save();
      }else{
      $slider->created_at =Carbon::now();
      $slider->save();
      }
      return redirect()->route('admin.slidersEdit',$slider->id);
    }

    public function slidersEdit($id){
      $slider =Attribute::where('type',1)->find($id);
      if(!$slider){
        Session()->flash('error','This Slider Are Not Found');
        return redirect()->route('admin.sliders');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['sliders']['all']);
      if($allPer && $slider->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.sliders');
      }

      return view('admin.sliders.slidersEdit',compact('slider'));
    }

    public function slidersUpdate(Request $r,$id){

      $slider =Attribute::where('type',1)->find($id);
      if(!$slider){
        Session()->flash('error','This Slider Are Not Found');
        return redirect()->route('admin.sliders');
      }

      $check = $r->validate([
            'name' => 'required|max:191',
            'location' => 'nullable|max:200',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }

      $activeSlider =Attribute::where('type',1)->where('location',$r->location)->whereNotIn('id',[$slider->id])->first();
      
      if($activeSlider && $r->location){
        Session::flash('error','This Location Have Already a slider');
        return back();
      }

      $slider->name=$r->name;
      $slider->description=$r->description;
      $slider->location=$r->location;
      $slider->seo_title=$r->vediolink;
      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$slider->id)->first();
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
          $media->src_id=$slider->id;
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
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',2)->where('src_id',$slider->id)->first();

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
          $media->src_id=$slider->id;
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
        $slider->slug=$slider->id;
       }else{
        if(Attribute::where('type',1)->where('slug',$slug)->whereNotIn('id',[$slider->id])->count() >0){
        $slider->slug=$slug.'-'.$slider->id;
        }else{
        $slider->slug=$slug;
        }
      }
      $slider->status =$r->status?'active':'inactive';
      $slider->editedby_id =Auth::id();
      $slider->save();


      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function slidersDelete(Request $r, $id){

        $slider =Attribute::where('type',1)->find($id);
        if(!$slider){
          Session()->flash('error','This Slider Are Not Found');
          return redirect()->route('admin.sliders');
        }

        //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['sliders']['all']);
        if($allPer && $slider->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.sliders');
        }

        //Sub Slider Items Delete
        foreach($slider->sliderItems as $slide){

          //Galleries  Media File Delete
            $medies =Media::where('src_type',3)->where('src_id',$slide->id)->get();
            foreach ($medies as  $media) {
                if(File::exists(public_path($media->file_url))){
                    File::delete(public_path($media->file_url));
                }
                $media->delete();
            }

          $slide->delete();

        }


      //Galleries  Media File Delete
      $sliderMedies =Media::where('src_type',3)->where('src_id',$slider->id)->get();
      foreach ($sliderMedies as  $media) {
            if(File::exists(public_path($media->file_url))){
                File::delete(public_path($media->file_url));
            }
            $media->delete();
        }

      $slider->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }



    public function slideAjax(Request $r, $id){
      
       $slider =Attribute::where('type',1)->find($id);
        if(!$slider){
          Session()->flash('error','This Slide Are Not Found');
          return redirect()->route('admin.sliders');
        }

        if($r->ajax())
        {
            $slides =Attribute::where('parent_id',$id)->orderBy('view','ASC')->where('status','<>','temp')->get();
            return Response()->json([
                'success' => true,
                'view' => View('admin.sliders.includes.slideItems',[
                    'slides'=>$slides,
                    'slider'=>$slider
                ])->render()
            ]);
        }

        return back();


    }


    public function slideCreate($id){
      $slider =Attribute::where('type',1)->find($id);
      if(!$slider){
        Session()->flash('error','This Slide Are Not Found');
        return redirect()->route('admin.sliders');
      }
      $sliderImage =Attribute::latest()->where('type',1)->where('parent_id',$slider->id)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$sliderImage){
      $sliderImage =new Attribute();
      $sliderImage->type =1;
      $sliderImage->parent_id =$slider->id;
      $sliderImage->status ='temp';
      $sliderImage->addedby_id =Auth::id();
      $sliderImage->save();
      }else{
      $sliderImage->created_at =Carbon::now();
      $sliderImage->save();
      }
      return redirect()->route('admin.slideEdit',$sliderImage->id);
    }

    public function slideEdit($id){
      $slide =Attribute::where('type',1)->find($id);
      if(!$slide){
        Session()->flash('error','This Slide Are Not Found');
        return redirect()->route('admin.sliders');
      }

      return  view('admin.sliders.slideEdit',compact('slide'));
    }

    public function slideUpdate(Request $r,$id){
      $slide =Attribute::where('type',1)->find($id);
      if(!$slide){
        Session()->flash('error','This Slide Are Not Found');
        return redirect()->route('admin.sliders');
      }

       $check = $r->validate([
            'name' => 'required|max:191',
            'uselayer' => 'nullable|max:200',
            'solidcolor' => 'nullable|max:200',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }

      $slide->name =$r->name;
      $slide->description=$r->description;
      $slide->seo_title=$r->link;
      $slide->icon=$r->solidcolor;
      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$slide->id)->first();
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
          $media->src_id=$slide->id;
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
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',2)->where('src_id',$slide->id)->first();

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
          $media->src_id=$slide->id;
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
        $slide->slug=$slide->id;
       }else{
        if(Attribute::where('type',1)->where('slug',$slug)->whereNotIn('id',[$slide->id])->count() >0){
        $slide->slug=$slug.'-'.$slide->id;
        }else{
        $slide->slug=$slug;
        }
      }
      $slide->status =$r->status?'active':'inactive';
      $slide->editedby_id =Auth::id();
      $slide->save();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }


    public function slideDrug(Request $r, $id){
      $slider =Attribute::where('type',1)->find($id);
      if(!$slider){
        Session()->flash('error','This Slider Are Not Found');
        return redirect()->route('admin.sliders');
      }



      for ($i=0; $i < count($r->slideid); $i++) { 
        $slide =Attribute::where('type',1)->find($r->slideid[$i]);
        if($slide){
          $slide->view=$i;
          $slide->save();
        }
      }
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    public function slideDelete($id){
      $slide =Attribute::where('type',1)->find($id);
      if(!$slide){
        Session()->flash('error','This Slide Are Not Found');
        return redirect()->route('admin.sliders');
      }

      //Galleries  Media File Delete
        $medies =Media::where('src_type',3)->where('src_id',$slide->id)->get();
        foreach ($medies as  $media) {
            if(File::exists(public_path($media->file_url))){
                File::delete(public_path($media->file_url));
            }
            $media->delete();
        }
      $slide->delete();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }



    //Sliders Function End


    //Galleries Function Start

    public function galleries(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['galleries']['all']);

      $galleries=Attribute::latest()->where('type',4)->where('status','<>','temp')->where('parent_id',null)
      ->where(function($q) use ($allPer) {
          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }
      })
      ->select(['id','name','location','type','created_at','addedby_id','status','fetured'])
      ->paginate(25);
      return view('admin.galleries.galleriesAll',compact('galleries','r'));

    }


    public function galleriesCreate(){
      $gallery =Attribute::latest()->where('type',4)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$gallery){
      $gallery =new Attribute();
      $gallery->type =4;
      $gallery->status ='temp';
      $gallery->addedby_id =Auth::id();
      $gallery->save();
      }else{
      $gallery->created_at =Carbon::now();
      $gallery->save();
      }
      return redirect()->route('admin.galleriesEdit',$gallery->id);
    }

    public function galleriesEdit($id){

      $gallery =Attribute::where('type',4)->find($id);
      if(!$gallery){
        Session()->flash('error','This Gallery Are Not Found');
        return redirect()->route('admin.galleries');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['galleries']['all']);
      if($allPer && $gallery->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.galleries');
      }

      return view('admin.galleries.galleriesEdit',compact('gallery'));

    }

    public function galleriesUpdate(Request $r,$id){

      $gallery =Attribute::where('type',4)->find($id);
      if(!$gallery){
        Session()->flash('error','This Gallery Are Not Found');
        return redirect()->route('admin.galleries');
      }

      $check = $r->validate([
            'name' => 'required|max:191',
            'location' => 'nullable|max:200',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }

      $activeGallery =Attribute::where('type',4)->where('location',$r->location)->whereNotIn('id',[$gallery->id])->first();
      
      if($activeGallery && $r->location){
        Session::flash('error','This Location Have Already a Gallery');
        return back();
      }

      $gallery->name=$r->name;
      $gallery->description=$r->description;
      $gallery->location=$r->location;
      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$gallery->id)->first();
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
          $media->src_id=$gallery->id;
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
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',2)->where('src_id',$gallery->id)->first();

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
          $media->src_id=$gallery->id;
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
        $gallery->slug=$gallery->id;
       }else{
        if(Attribute::where('type',1)->where('slug',$slug)->whereNotIn('id',[$gallery->id])->count() >0){
        $gallery->slug=$slug.'-'.$gallery->id;
        }else{
        $gallery->slug=$slug;
        }
      }
      $gallery->status =$r->status?'active':'inactive';
      $gallery->editedby_id =Auth::id();
      $gallery->save();


      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function galleriesDelete(Request $r, $id){

        $gallery =Attribute::where('type',4)->find($id);
        if(!$gallery){
          Session()->flash('error','This Gallery Are Not Found');
          return redirect()->route('admin.galleries');
        }

        //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['galleries']['all']);
        if($allPer && $gallery->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.galleries');
        }


      //Galleries  Media all File Delete
      $galleryMedies =Media::where('src_type',3)->where('src_id',$gallery->id)->get();

      foreach ($galleryMedies as  $media) {
            if(File::exists(public_path($media->file_url))){
                File::delete(public_path($media->file_url));
            }
            $media->delete();
        }

      $gallery->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }


    public function galleriesImagesCreate(Request $r,$id){
      $gallery =Attribute::where('type',4)->find($id);
        if(!$gallery){
          Session()->flash('error','This Gallery Are Not Found');
          return redirect()->route('admin.galleries');
        }

        $check = $r->validate([
            //'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

      ///////Gallery Images Uploard End////////////
        
        $files=$r->file('images');
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
                  $media->src_type=3;
                  $media->use_Of_file=3;
                  $media->src_id=$gallery->id;
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

        ///////Gallery Image Uploard End////////////

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function galleriesImagesUpdate(Request $r,$id){

      $gallery =Attribute::where('type',4)->find($id);
        if(!$gallery){
          Session()->flash('error','This Gallery Are Not Found');
          return redirect()->route('admin.galleries');
        }


      if($r->action==1 && $r->imageid){

        for ($i=0; $i < count($r->imageid); $i++) { 
            $image =$gallery->galleryImages()->where('id',$r->imageid[$i])->first();

            if($image){
              $image->drag=$i;
              $image->alt_text=$r->name[$i];
              $image->description=$r->description[$i];
              $image->save();
            }


        }

      }elseif($r->action==2 && $r->checkid){

        for ($i=0; $i < count($r->checkid); $i++) { 
            $image =$gallery->galleryImages()->where('id',$r->checkid[$i])->first();
            if($image){
              if(File::exists(public_path($image->file_url))){
                  File::delete(public_path($image->file_url));
              }
             $image->delete();
            }
          
          }


      }

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    //Galleries Function End

    public function expensesTypes(Request $r){

      if($r->isMethod('post')){

          $check = $r->validate([
              'name' => 'required|max:191',
          ]);

          $expensesType  = new Attribute();
          $expensesType->type=12;
          $expensesType->name=$r->name;
          $expensesType->description=$r->description;
          $expensesType->status='active';
          $expensesType->addedby_id=Auth::id();
          $expensesType->save();

        Session()->flash('success','Type Added Successfully Done!');
        return redirect()->route('admin.expensesTypes');

      }

    $types =Attribute::where('type',12)
    ->where(function($q) use($r) {
        if($r->search){
            $q->where('name','LIKE','%'.$r->search.'%');
        }
    })
    ->select(['id','type','name','description','addedby_id','created_at','status'])
    ->paginate(25);

      return view('admin.accounts.expenses.expensesTypes',compact('types','r'));
    }


    public function expensesTypesManage(Request $r,$id,$type){
      $expensesType =Attribute::where('type',12)->find($id);
      
      if(!$expensesType){
        Session()->flash('error','This Type Are Not Found');
        return redirect()->route('admin.expensesTypes');
      }

      if($type=='update'){
        $check = $r->validate([
              'name' => 'required|max:191',
          ]);

        $expensesType->name=$r->name;
        $expensesType->description=$r->description;
        $expensesType->editedby_id=Auth::id();
        $expensesType->save();

        Session()->flash('success','Type Update Successfully Done!');
        return redirect()->route('admin.expensesTypes');

      }

      if($type=='delete'){

        $expensesType->delete();

        Session()->flash('success','Type Deleted Successfully Done!');
        return redirect()->route('admin.expensesTypes');
      }
      
      Session()->flash('error','Unknown Type Action Not Allowed');
      return redirect()->route('admin.expensesTypes');

    }


    
    public function expensesList(Request $r){


      if($r->isMethod('post')){
          
          $check = $r->validate([
              'date' => 'required',
              'type' => 'required|numeric',
              'method' => 'required|numeric',
              'methodoption' => 'required|numeric',
              'amount' => 'required|numeric',
          ]);


          $method =Attribute::where('type',11)->find($r->method);
          $methodOption =Attribute::where('type',11)->find($r->methodoption);
          
          if(!$method || !$methodOption){
            Session()->flash('error','Method Type Are Found');
            return redirect()->route('admin.expensesList');
          }

          if($r->amount > $methodOption->amounts){
            Session()->flash('error',$method->name.' Balance Are Not Avialable');
            return redirect()->route('admin.expensesList');
          }

          $methodOption->amounts -=$r->amount;
          $methodOption->save();

          $expense  = new Transaction();
          $expense->type=4;
          $expense->src_id=$r->type;
          $expense->billing_name=general()->title;
          $expense->billing_mobile=general()->mobile;
          $expense->billing_email=general()->email;
          $expense->billing_address=general()->address_one;
          $expense->amount=$r->amount;
          $expense->method_id=$r->method;
          $expense->method_option_id=$r->methodoption;
          $expense->billing_note=$r->description;
          $expense->status='success';
          $expense->addedby_id=Auth::id();
          $expense->created_at=$r->date?$r->date.' '.Carbon::now()->format('H:i:s'):Carbon::now();
          $expense->save();

        Session()->flash('success','Expense Added Successfully Done!');
        return redirect()->route('admin.expensesList');

      }


      $expenses =Transaction::where('type',4)->latest()
      ->where(function($q) use($r){

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
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

                $q->whereDate('addedby_at','>=',$from)->whereDate('addedby_at','<=',$to);
            }

      })
      ->paginate(25);

      $types =Attribute::where('type',12)->select(['id','name'])->get();

      $methods =Attribute::where('type',11)->where('status','active')->where('parent_id',null)->select(['id','name'])->orderBy('view','asc')->get();

      return view('admin.accounts.expenses.expensesList',compact('expenses','types','r','methods'));
    }

    public function expensesListManage(Request $r,$id,$type=null){

        $expense =Transaction::where('type',4)->find($id);
      
        if(!$expense){
          Session()->flash('error','Expense Are Not Found');
          return redirect()->route('admin.expensesList');
        }

        if($type=='update'){

            $check = $r->validate([
                'date' => 'required',
                'type' => 'required|numeric',
                'method' => 'required|numeric',
                'methodoption' => 'required|numeric',
                'amount' => 'required|numeric',
            ]);


            $method =Attribute::where('type',11)->find($r->method);
            
            $methodOption =Attribute::where('type',11)->find($r->methodoption);
            
            if(!$method || !$methodOption){
              Session()->flash('error','Method Type Are Found');
              return redirect()->route('admin.expensesList');
            }

            if($r->amount > $methodOption->amounts){
              Session()->flash('error',$method->name.' Balance Are Not Avialable');
              return redirect()->route('admin.expensesList');
            }
            
            $amount =0;

            if($expense->amount > $r->amount){
              $amount =$expense->amount - $r->amount;
              $methodOption->amounts +=$amount;
            }elseif($expense->amount < $r->amount){
              $amount =$r->amount - $expense->amount;
              $methodOption->amounts -=$amount;
            }

            $methodOption->save();

            $expense->src_id=$r->type;
            $expense->billing_name=general()->title;
            $expense->billing_mobile=general()->mobile;
            $expense->billing_email=general()->email;
            $expense->billing_address=general()->address_one;
            $expense->amount=$r->amount;
            $expense->method_id=$r->method;
            $expense->method_option_id=$r->methodoption;
            $expense->billing_note=$r->description;
            $expense->status='success';
            $expense->editedby_id=Auth::id();
            $expense->created_at=$r->date?$r->date.' '.Carbon::now()->format('H:i:s'):Carbon::now();
            $expense->save();


          Session()->flash('success','Expense Update Successfully Done!');
          return redirect()->route('admin.expensesList');

        }

        if($type=='delete'){

          if($expense->methodOption){

            $methodOption =$expense->methodOption;

            $methodOption->amounts + $r->amount;
            $methodOption->save();

          }

          $expense->delete();

          Session()->flash('success','Type Deleted Successfully Done!');
          return redirect()->route('admin.expensesTypes');

        }

        Session()->flash('error','Unknown Type Action Not Allowed');
        return redirect()->route('admin.expensesTypes');


    }


    public function accountsList(Request $r){
        
        if($r->type=='add-method'){

            $method = Attribute::where('type',11)->where('status','temp')->where('addedby_id',auth::id())->first();

            if(!$method){
              $method =new Attribute();
              $method->type=11;
              $method->view=1;
              $method->status='temp';
            }
    
            $method->created_at=Carbon::now();
            $method->save();
            
            return redirect()->route('admin.accountsEdit',['edit',$method->id]);
            
        }
        
        
      $methods =Attribute::where('type',11)->where('status','<>','temp')->where('parent_id',null)->select(['id','name','status'])->orderBy('view','asc')->get();
      return view('admin.accounts.accountsList',compact('methods'));
      
    }
    
    public function accountsEdit(Request $r,$type,$id){
        
        if($type=='paymentoptionUpdate' || $type=='paymentoptionDelete'){
            
            $methodOption = Attribute::where('type',11)->find($id);
          
            if(!$methodOption){
            Session::flash('error','Method Are Not Found!');
            return redirect()->route('admin.accountsList');
            }

            if($type=='paymentoptionDelete'){
               $methodOption->delete(); 
               
                Session::flash('success','Method Option Deleted Successfully Done!');
                return redirect()->back();
            }
            
            $check = $r->validate([
              'update_option_name' => 'required|max:191',
              'update_option_status' => 'required|max:50',
            ]);
    
            $methodOption->name=$r->update_option_name;
            $methodOption->description=$r->update_option_description;
            $methodOption->status=$r->update_option_status;
            $methodOption->save();
            
            Session::flash('success','Method Option Updated Successfully Done!');
            return redirect()->back();
          
        }else{
        
        $method = Attribute::where('type',11)->find($id);
        
        if(!$method){
          Session::flash('error','Method Are Not Found!');
          return redirect()->route('admin.accountsList');
        }
        
        }
        
        
        
        if($type=='method-update'){
            
            $check = $r->validate([
              'name' => 'required|max:191',
              'status' => 'required|max:50',
              'serial' => 'required|numeric',
            ]);

          $method->name=$r->name;
          $method->description=$r->description;
          $method->status=$r->status;
          $method->view=$r->serial?:1;
          $method->save();

          Session::flash('success','Method Are Update Successfully Done!');
          return redirect()->back();
          
        }
        
        
        if($type=='paymentoption'){
         
            $check = $r->validate([
              'option_name' => 'required|max:191',
              'option_status' => 'required|max:50',
            ]);

          $methodOption =new Attribute();
          $methodOption->parent_id=$method->id;
          $methodOption->type=11;
          $methodOption->name=$r->option_name;
          $methodOption->description=$r->option_description;
          $methodOption->status=$r->option_status;
          $methodOption->save();

          Session::flash('success','Method Option Added Successfully Done!');
          return redirect()->back();

        }
        
        if($type!='edit'){
            return 'stop';
          Session::flash('error','Unknown Type Action Not Allow!');
          return redirect()->route('admin.accountsEdit',['edit',$method->id]);
        }

        return view('admin.accounts.accountsEdit',compact('method','type'));
    }
    
    
    

    public function accountsTransfer(Request $r){

      if($r->isMethod('post')){
           
            $check = $r->validate([
                'date' => 'required',
                'frommethod' => 'required|numeric',
                'frompaymentOption' => 'required|numeric',
                'tomethod' => 'required|numeric',
                'topaymentOption' => 'required|numeric',
                'amount' => 'required|numeric',
            ]);

          $formMethod =Attribute::where('type',11)->find($r->frommethod);
            
          $formMethodOption =Attribute::where('type',11)->find($r->frompaymentOption);
            
            if(!$formMethod || !$formMethodOption){
              Session()->flash('error','Method Type Are Found');
              return redirect()->route('admin.accountsTransfer');
            }

          $toMethod =Attribute::where('type',11)->find($r->tomethod);
            
          $toMethodOption =Attribute::where('type',11)->find($r->topaymentOption);
            
            if(!$toMethod || !$toMethodOption){
              Session()->flash('error','Method Type Are Found');
              return redirect()->route('admin.accountsTransfer');
            }

          
          if($r->amount > $formMethodOption->amounts){
              Session()->flash('error','Amount Are Not Avialable This To method');
              return redirect()->route('admin.accountsTransfer');
          }


          $transfer  = new Transaction();
          $transfer->type=5;
          $transfer->src_id=$r->frommethod;
          $transfer->transection_id=$r->frompaymentOption;
          $transfer->billing_name=general()->title;
          $transfer->billing_mobile=general()->mobile;
          $transfer->billing_email=general()->email;
          $transfer->billing_address=general()->address_one;
          $transfer->amount=$r->amount;
          $transfer->method_id=$r->tomethod;
          $transfer->method_option_id=$r->topaymentOption;
          $transfer->billing_note=$r->description;
          $transfer->status='success';
          $transfer->addedby_id=Auth::id();
          $transfer->created_at=$r->date?$r->date.' '.Carbon::now()->format('H:i:s'):Carbon::now();
          $transfer->save();

          $formMethodOption->amounts -=$transfer->amount;
          $formMethodOption->save();

          $toMethodOption->amounts +=$transfer->amount;
          $toMethodOption->save();

        Session()->flash('success','Accounts Transfer Successfully Done!');
        return redirect()->route('admin.accountsTransfer');

      }


      $transfers =Transaction::where('type',5)->where('status','<>','temp')
      ->where(function($qq) use($r) {
          
            if($r->method){
              $qq->where('method_id',$r->method);
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

                $qq->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to);

            }

      })
      ->select(['id','src_id','transection_id','billing_note','method_id','method_option_id','amount','created_at','status','addedby_id'])
      ->paginate(50)->appends([
        'method'=>$r->method,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

      $methods =Attribute::where('type',11)->where('status','<>','temp')->where('parent_id',null)->select(['id','name','status'])->orderBy('view','asc')->get();
      

      
      return view('admin.accounts.accountsTransfer',compact('transfers','r','methods'));
    }
    
    public function accountsTransferUpdate(Request $r,$type,$id){
        
        $transfer =Transaction::where('type',5)->find($id);
        
        if(!$transfer){
            Session()->flash('error','Transfer Are Not Found');
            return redirect()->route('admin.accountsTransfer');    
        }
        
        if($type=='update'){
            
        $check = $r->validate([
            'date' => 'required',
            'frommethod' => 'required|numeric',
            'frompaymentOption' => 'required|numeric',
            'tomethod' => 'required|numeric',
            'topaymentOption' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
            
          return $r;
          
        }
        
        if($type=='delete'){
            
            $formMethod =Attribute::where('type',11)->find($transfer->src_id);
            
            $formMethodOption =Attribute::where('type',11)->find($transfer->transection_id);
            
            if(!$formMethod || !$formMethodOption){
              Session()->flash('error','This Transection Can Not Deleted');
              return redirect()->route('admin.accountsTransfer');
            }
            
            $toMethod =Attribute::where('type',11)->find($transfer->method_id);
            
            $toMethodOption =Attribute::where('type',11)->find($transfer->method_option_id);
            
            if(!$toMethod || !$toMethodOption){
              Session()->flash('error','This Transection Can Not Deleted');
              return redirect()->route('admin.accountsTransfer');
            }

          
          if($transfer->amount > $toMethodOption->amounts){
              Session()->flash('error','Amount Are Not Avialable To method');
              return redirect()->route('admin.accountsTransfer');
          }
          
          $formMethodOption->amounts +=$transfer->amount;
          $formMethodOption->save();

          $toMethodOption->amounts -=$transfer->amount;
          $toMethodOption->save();

        Session()->flash('success','Transfer Deleted Successfully Done!');
        return redirect()->route('admin.accountsTransfer');
          
          
        }
        
        
    }


    public function accountsBalance(Request $r){

      if($r->isMethod('post')){
        

          $check = $r->validate([
                'date' => 'required',
                'method' => 'required|numeric',
                'paymentOption' => 'required|numeric',
                'type' => 'required|max:50',
                'amount' => 'required|numeric',
            ]);

        $method =Attribute::where('type',11)->find($r->method);
            
        $methodOption =Attribute::where('type',11)->find($r->paymentOption);
          
          if(!$method || !$methodOption){
            Session()->flash('error','Method Type Are Found');
            return redirect()->route('admin.accountsBalance');
          }

        if($r->type=='Withdrawal'){
          

          if($r->amount > $methodOption->amounts){
              Session()->flash('error','Amount Are Not Avialable This To method');
              return redirect()->route('admin.accountsBalance');
          }

          $methodOption->amounts-=$r->amount;
          $methodOption->save();

        }else{
          $methodOption->amounts+=$r->amount;
          $methodOption->save();
        }

          $transfer  = new Transaction();
          if($r->type=='Withdrawal'){
            $transfer->type=6;
          }else{
            $transfer->type=7;
          }
          $transfer->billing_name=general()->title;
          $transfer->billing_mobile=general()->mobile;
          $transfer->billing_email=general()->email;
          $transfer->billing_address=general()->address_one;
          $transfer->amount=$r->amount;
          $transfer->method_id=$r->method;
          $transfer->method_option_id=$r->paymentOption;
          $transfer->billing_note=$r->description;
          $transfer->status='success';
          $transfer->addedby_id=Auth::id();
          $transfer->created_at=$r->date?$r->date.' '.Carbon::now()->format('H:i:s'):Carbon::now();
          $transfer->save();

          Session()->flash('success','Accounts '.$r->type.' Successfully Done!');
          return redirect()->route('admin.accountsBalance');


      }

      $withdrawals =Transaction::latest()->where('type',6)->paginate(50);
      $deposits =Transaction::latest()->where('type',7)->paginate(50);
      $methods =Attribute::where('type',11)->where('status','<>','temp')->where('parent_id',null)->select(['id','name','status'])->orderBy('view','asc')->get();

      return view('admin.accounts.balanceList',compact('methods','withdrawals','deposits'));
    }


    public function reportsAll(Request $r,$type){
        
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
        
        $day =Carbon::parse($from)->diffInDays(Carbon::parse($to))+1;
        
        
        if($type=='products'){
            
            $brands = Attribute::where('type',2)->where('status','<>','temp')->where('parent_id',null)->get();
            $categories = Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->get();
            
            $products=array();
            
            if($r->quantity || $r->category || $r->price || $r->status || $r->search || $r->startDate || $r->endDate){
                
                $products = Post::latest()->where('type',2)->where('status', '<>','temp')
                    ->where(function($qq)  use ($r)  {
        
                        if($r->quantity)
                        {
                            $qq->where('quantity','<=',$r->quantity);
                        }
                        if($r->category)
                        {
                            $qq->whereHas('ctgProducts',function($q)use($r){
                                $q->where('reff_id',$r->category);
                            });
                        }
        
                        if($r->price)
                        {
                            $qq->where('final_price','<=',$r->price);
                        }
    
                        if($r->status!='all')
                        {
                            $qq->where('status',$r->status);
                        }
        
                        if($r->search)
                        {
                             $qq->where('name','like',"%{$r->search}%");
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
        
                            $qq->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to);
        
                        }
        
                    })
                    ->select(['id','name','purchase_price','final_price','quantity','created_at','status'])
                    ->get();
                
            }
    
            return view('admin.reports.productReports',compact('products','categories','brands','r','type','from','to','day'));
            
        }elseif($type=='sales-product'){
            

            $categories = Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->get();
            
            $products=array();
            
            if($r->category || $r->search || $r->startDate || $r->endDate){
                
               
                
                
                $products = Post::latest()->where('type',2)->where('status', '<>','temp')
                    ->where(function($qq)  use ($r,$from,$to)  {
                        if($r->category)
                        {
                            $qq->whereHas('ctgProducts',function($q)use($r){
                                $q->where('reff_id',$r->category);
                            });
                        }
        
                        if($r->search)
                        {
                             $qq->where('name','like',"%{$r->search}%");
                        }
                        
                        $qq->whereHas('salesAll', function ($qqq) use ($from, $to) {
                            $qqq->whereBetween('created_at', [
                                \Carbon\Carbon::parse($from)->format('Y-m-d H:i:s'),
                                \Carbon\Carbon::parse($to)->format('Y-m-d H:i:s')
                            ])
                            ->where('quantity', '>', 0)
                            ->where('final_price', '>', 0);
                        });
        
                    })
                    ->select(['id','name'])
                    ->get();
                
                    $products->each(function ($product) use($from,$to){
                        $product->total_sale = $product->saleReportDateWise('amount',$from,$to);
                        $product->total_qty = $product->saleReportDateWise('qty',$from,$to);
                    });
                
            }
         
            return view('admin.reports.productSalesReport',compact('products','categories','type','from','to','day'));
            
        }elseif($type=='customer-orders'){
            
            
            $orders=array();
            
            if($r->status || $r->payment || $r->search || $r->startDate || $r->endDate || $r->shippingDate){

                $orders =Order::latest()->where('order_type','customer_order')->where('order_status','<>','temp')
                        ->where(function($qq)  use ($r)  {
        
                        if($r->status){
                            $qq->where('order_status',$r->status);
                        }
                        
                        if($r->payment){
                            $qq->where('payment_status',$r->payment);
                        }
                        
                        if($r->search){
                           $qq->where('invoice','LIKE','%'.$r->search.'%')->orWhere('name','LIKE','%'.$r->search.'%')->orWhere('email','LIKE','%'.$r->search.'%')->orWhere('mobile','LIKE','%'.$r->search.'%'); 
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
        
                            $qq->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to);
        
                        }
                        
                        if($r->shippingDate){
                            
                            $shipDate =Carbon::parse($r->shippingDate)->format('m/d/Y');
                            $qq->where('delivery_date',$shipDate);
                        }
                        
                    })
                    ->get();
            
            }
            
            
            return view('admin.reports.customerOrdersReports',compact('orders','r','type','from','to'));
            
        }elseif($type=='pos-orders'){
            $orders=array();
            
            if($r->status || $r->payment || $r->search || $r->startDate || $r->endDate){
                
                $orders =Order::latest()->where('order_type','pos_order')->where('order_status','<>','temp')
                        ->where(function($qq)  use ($r)  {
        
                        if($r->status){
                            $qq->where('order_status',$r->status);
                        }
                        
                        if($r->payment){
                            $qq->where('payment_status',$r->payment);
                        }
                        
                        if($r->search){
                           $qq->where('invoice','LIKE','%'.$r->search.'%')->orWhere('name','LIKE','%'.$r->search.'%')->orWhere('email','LIKE','%'.$r->search.'%')->orWhere('mobile','LIKE','%'.$r->search.'%'); 
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
        
                            $qq->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to);
        
                        }
                        
                    })
                    ->get();
            
            }
            
            return view('admin.reports.POSOrdersReports',compact('orders','r','type','from','to'));
            
        }elseif($type=='inventory-stock'){
            
            $inventories=array();
            if($r->status || $r->search || $r->startDate || $r->endDate){
   

                $inventories = Order::latest()
                  ->where('src_id',null)
                  ->where(function($q) use ($r) {
                   
                     if($r->status){
                        if($r->status=='1'){
                            $status =$r->status;
                        }else{
                            $status =0;
                        }
                        
                       $q->where('type',$status);
                     }
                    
                     if($r->search){
                             
                          $q->whereHas('seller',function($qq) use ($r) {
                              $qq->where('title','LIKE','%'.$r->search.'%');
                          });
                          
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
                    
                  })
                  ->get();
                
            }
            return view('admin.reports.inventoryStockReports',compact('inventories','r','type','from','to'));
        }else{
            
            $customerOrders  = Order::latest()->where('order_type','customer_order')->where('order_status','<>','temp')
                            ->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)
                            ->get();
                            
            $POSOrders =$orders =Order::latest()->where('order_type','pos_order')->where('order_status','<>','temp')
                            ->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)
                            ->get();
                            
            $products = Post::latest()->where('status', '<>','temp')->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)
                            ->get();
                            
            $inventories = Order::latest()
                  ->where('user_id',null)
                  ->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)
                  ->get();
            
            $reports =array(
                    'ordersTotal'=>$customerOrders->count(),
                    'ordersPending'=>$customerOrders->where('order_status','pending')->count(),
                    'ordersConfirmed'=>$customerOrders->where('order_status','confirmed')->count(),
                    'ordersShipped'=>$customerOrders->where('order_status','shipped')->count(),
                    'ordersDelivered'=>$customerOrders->where('order_status','delivered')->count(),
                    'ordersCancelled'=>$customerOrders->where('order_status','cancelled')->count(),
                    'ordersPaid'=>$customerOrders->where('payment_status','paid')->count(),
                    'ordersDue'=>$customerOrders->whereIn('payment_status',['unpaid','partial'])->count(),
                    'ordersSales'=>$customerOrders->sum('grand_total'),
                    'ordersSalesPaid'=>$customerOrders->where('payment_status','paid')->sum('grand_total'),
                    'ordersSalesDue'=>$customerOrders->whereIn('payment_status',['unpaid','partial'])->sum('due_amount'),
                );
                  
                  
     
            return view('admin.reports.summeryReports',compact('r','type','from','to','reports','day'));
        }
         
        
    }



    public function themeSetting(Request $r){
      
      if($r->type=='add'){

          $postData =new PostExtra();
          $postData->type=4;
          $postData->status='active';
          $postData->save();
          
          Session()->flash('success','New Added Successfully Done');
          return redirect()->route('admin.themeSettingEdit',$postData->id);
      }
      
      $homeDatas =PostExtra::where('type',4)->where('parent_id',null)->orderBy('drag','asc')->get();
      
      return view('admin.theme-setting.themeSetting',compact('homeDatas'));
    }
    
    public function themeSettingEdit(Request $r,$id){
        
        $homedata =PostExtra::where('type',4)->find($id);
        
        if(!$homedata){
            Session()->flash('error','Home Data Not Found');
            return redirect()->route('admin.themeSetting');
        }
        
        if($r->type=="delete"){
            $homedata->homeDataIds()->delete();
            $homedata->delete();
            
            Session()->flash('success','Data Delete Successfully Done!');
            return redirect()->route('admin.themeSetting');
        }
        
        $searchProducts =null;
        
        if($r->ajax()){
            
            if($r->type=='search'){
                
                $searchProducts =Post::latest()->where('type',2)->where('status','active')->where('stock_status',true)->where('quantity','>',0)
                ->where(function($q) use($r){
                    
                    if($r->type=='search' && $r->key){
                        $q->where('name','like','%'.$r->key.'%');
                        $q->orWhere('sku_code','like','%'.$r->key.'%');
                        $q->orWhere('bar_code','like',$r->key);
                    }

                })
                ->select(['id','name','final_price','quantity','sku_code'])
                ->paginate(25)->appends([
                  'key'=>$r->key,
                  'barcode'=>$r->barcode,
                ]);
                
               $datas =view('admin.purchase.includes.searchResult',compact('searchProducts'))->render();
                
                return Response()->json([
                    'success' => true,
                    'view' => $datas,
                ]); 
            }
            
            if($r->type=='addproduct' && $r->id){

               $product =Post::latest()->where('type',2)->where('status','active')->find($r->id);

               if($product){
                   
                    $item =PostExtra::where('parent_id',$homedata->id)->where('src_id',$product->id)->first();
                            
                    if(!$item){
                        $item =new PostExtra();
                        $item->parent_id =$homedata->id;
                        $item->src_id =$product->id;
                        $item->type =4;
                        $item->save();
                    }
                    
               }
               
            }
            
            $datas =view('admin.theme-setting.includes.homeProducts',compact('homedata'))->render();
            
            return Response()->json([
                    'success' => true,
                    'view' => $datas,
                ]);
            
        }
        
        return view('admin.theme-setting.themeSettingEdit',compact('homedata','searchProducts'));
    }
    
    
    public function themeSettingUpdate(Request $r,$id){
        $homedata =PostExtra::where('type',4)->find($id);

        if(!$homedata){
            Session()->flash('error','Home Data Not Found');
            return redirect()->route('admin.themeSetting');
        }
        
        $check = $r->validate([
            'title' => 'required|max:191',
            'bg_color' => 'nullable|max:100',
            'title_color' => 'nullable|max:100',
            'serial' => 'required|numeric',
            'limit' => 'required|numeric',
            'product_view' => 'required|numeric',
            'product_type' => 'required|numeric',
            'status' => 'required',
            'banner_link' => 'nullable|max:100',
             'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        
         ///////Banner Uploard Start////////////
        if($r->hasFile('banner')){
         $file=$r->banner;
         $media =Media::latest()->where('src_type',9)->where('use_Of_file',2)->where('src_id',$homedata->id)->first();
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
          $media->src_type=9;
          $media->use_Of_file=2;
          $media->src_id=$homedata->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();
      }
        

        $homedata->name = $r->title;
        $homedata->bg_color = $r->bg_color;
        $homedata->title_color = $r->title_color;
        $homedata->drag = $r->serial?:0;
        $homedata->product_limit = $r->limit?:0;
        $homedata->product_view = $r->product_view?:0;
        $homedata->product_type = $r->product_type?:0;
        $homedata->status = $r->status;
        $homedata->banner_link = $r->banner_link;
        $homedata->banner_status = $r->banner_status?true:false;
        $homedata->save();
        
        if(isset($r->delete)){
            
            $homedata->homeDataIds()->whereIn('src_id',$r->delete)->delete();
            
        }
        
        
        Session()->flash('success','Data Updated Successfully Done');
        return redirect()->back();
        
    }



  // User Management Function Start

  public function usersAdmin(Request $r){
    $users =User::latest()->whereIn('status',[0,1])->where('admin',true)
    ->where(function($q) use($r) {

        if($r->search){
            $q->where('name','LIKE','%'.$r->search.'%');
            $q->orWhere('email','LIKE','%'.$r->search.'%');
            $q->orWhere('mobile','LIKE','%'.$r->search.'%');
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

            $q->whereDate('addedby_at','>=',$from)->whereDate('addedby_at','<=',$to);
        }

    })
    ->select(['id','permission_id','name','email','mobile','addedby_at','addedby_id','status'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

    return view('admin.users.admins.users',compact('users','r'));
  }

  public function usersAdminAdd(Request $r){

    if(filter_var($r->username, FILTER_VALIDATE_EMAIL)){
      $user =User::latest()->whereIn('status',[0,1])->where('email',$r->username)->first();
    }else{
      $user =User::latest()->whereIn('status',[0,1])->where('mobile',$r->username)->first();
    }

    if(!$user){
        Session()->flash('error','This User Are Not Register');
        return redirect()->route('admin.usersAdmin');
    }

    if($user->admin){
        Session()->flash('error','This User Are already Admin Authorize');
        return redirect()->route('admin.usersAdmin');
    }

    $user->admin=true;
    $user->addedby_at=Carbon::now();
    $user->save();

    Session()->flash('success','User Are Successfully Admin Authorize Done!');

    return redirect()->route('admin.usersAdmin');
    
  }

  public function usersAdminEdit ($id,$type){

      $user=User::whereIn('status',[0,1])->where('admin',true)->find($id);

      if(!$user){
        Session()->flash('error','This Admin User Are Not Found');
        return redirect()->route('admin.usersAdmin');
      }
      
      $roles =Permission::latest()->where('status','active')->get();

      return view('admin.users.admins.editUser',compact('user','roles','type'));
  }

  

  public function usersAdminUpdate(Request $r,$id,$type){

    $user=User::whereIn('status',[0,1])->where('admin',true)->find($id);

      if(!$user){
        Session()->flash('error','This Admin User Are Not Found');
        return redirect()->route('admin.usersAdmin');
      }

      if($type=='profile'){

       $check = $r->validate([
            'name' => 'required|max:100|unique:users,name,'.$user->id,
            'email' => 'required|max:100|unique:users,email,'.$user->id,
            'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
            'first_name' => 'nullable|max:100',
            'last_name' => 'nullable|max:100',
            'company_name' => 'nullable|max:100',
            'gender' => 'nullable|max:10',
            'address' => 'nullable|max:191',
            'prefecture' => 'nullable|numeric',
            'city' => 'nullable|max:191',
            'postal_code' => 'nullable|max:20',
            'role' => 'nullable|numeric',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

      $user->name =$r->name;
      $user->mobile =$r->mobile;
      $user->first_name =$r->first_name;
      $user->last_name =$r->last_name;
      $user->company_name =$r->company_name;
      $user->email =$r->email;
      $user->gender =$r->gender;
      $user->address_line1 =$r->address;
      $user->district =$r->prefecture;
      $user->city_name =$r->city;
      $user->postal_code =$r->postal_code;
      $user->permission_id =$r->role;

        ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$user->id)->first();
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
          $media->src_type=6;
          $media->use_Of_file=1;
          $media->src_id=$user->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();

      }

      $user->status=$r->status?true:false;
      $user->save();

      Session()->flash('success','Your Updated Are Successfully Done!');
      return redirect()->route('admin.usersAdminEdit',[$user->id,'profile']);

     }else if($type=='change-password'){

          $validator = Validator::make($r->all(), [
              'old_password' => 'required|string|min:8',
              'password' => 'required|string|min:8|confirmed|different:old_password',
          ]);
         
          if($validator->fails()){
            
              return redirect()->route('admin.usersAdminEdit',[$user->id,'change-password'])->withErrors($validator)->withInput();
          }

          if(Hash::check($r->old_password, $user->password)){
            $user->password_show=$r->password;
            $user->password=Hash::make($r->password);
            $user->update();
    
            Session()->flash('success','Your Are Successfully Done');
            return redirect()->route('admin.usersAdminEdit',[$user->id,'change-password']);
          }else{
          Session()->flash('error','Carrent Password Are Not Match');
          return redirect()->route('admin.usersAdminEdit',[$user->id,'change-password']);
          }

     }else{
      Session()->flash('error','Undefined route Action Please Try Agin?');
      return redirect()->route('admin.usersAdminEdit',[$user->id,'profile']);
     }


  }

  public function usersAdminDelete($id){

    $user=User::whereIn('status',[0,1])->where('admin',true)->find($id);
      if(!$user){
        Session()->flash('error','This Admin User Are Not Found');
        return redirect()->route('admin.usersAdmin');
    }

    $user->admin=false;
    $user->addedby_at=null;
    $user->save();

    Session()->flash('success','Admin User Are Removed Successfully Done');
    return redirect()->route('admin.usersAdmin');



  }



  public function usersCustomer(Request $r){

    $users =User::latest()->whereIn('status',[0,1])
    ->where(function($q) use($r) {

        if($r->search){
            $q->where('name','LIKE','%'.$r->search.'%');
            $q->orWhere('email','LIKE','%'.$r->search.'%');
            $q->orWhere('mobile','LIKE','%'.$r->search.'%');
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

    })
    ->select(['id','permission_id','name','email','mobile','created_at','addedby_id','status'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

    return view('admin.users.customers.users',compact('users','r'));
  }

  public function usersCustomerAdd(){

    $roles =Permission::latest()->where('status','active')->get();
    return view('admin.users.customers.addUser',compact('roles'));
  }

  public function usersCustomerPost(Request $r)
  {

     $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100|unique:users,email',
          'mobile' => 'nullable|max:20|unique:users,mobile',
          'password' => 'required|string|min:8',
      ]);


      $user =new User();
      $user->name =$r->name;
      $user->mobile =$r->mobile;
      $user->email =$r->email;
      $user->password_show=$r->password;
      $user->password=Hash::make($r->password);
      $user->save();

      Session()->flash('success','New user Register Are Successfully Done!');
      return redirect()->route('admin.usersCustomerEdit',[$user->id,'profile']);

  }

  public function usersCustomerEdit($id,$type){
     $user=User::whereIn('status',[0,1])->find($id);
      if(!$user){
        Session()->flash('error','This User Are Not Found');
        return redirect()->route('admin.usersCustomer');
      }
    $roles =Permission::latest()->where('status','active')->get();
    return view('admin.users.customers.editUser',compact('user','roles','type'));
  }

  public function usersCustomerUpdate(Request $r,$id,$type)
  {

     $user=User::whereIn('status',[0,1])->find($id);
      if(!$user){
        Session()->flash('error','This User Are Not Found');
        return redirect()->route('admin.usersCustomer');
      }
     
      if($type=='profile'){

       $check = $r->validate([
            'name' => 'required|max:100|unique:users,name,'.$user->id,
            'email' => 'required|max:100|unique:users,email,'.$user->id,
            'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
            'first_name' => 'nullable|max:100',
            'last_name' => 'nullable|max:100',
            'company_name' => 'nullable|max:100',
            'gender' => 'nullable|max:10',
            'address' => 'nullable|max:191',
            'prefecture' => 'nullable|numeric',
            'city' => 'nullable|max:191',
            'postal_code' => 'nullable|max:20',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

      $user->name =$r->name;
      $user->first_name =$r->first_name;
      $user->last_name =$r->last_name;
      $user->company_name =$r->company_name;
      $user->mobile =$r->mobile;
      $user->email =$r->email;
      $user->gender =$r->gender;
      $user->address_line1 =$r->address;
      $user->district =$r->prefecture;
      $user->city_name =$r->city;
      $user->postal_code =$r->postal_code;

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$user->id)->first();
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
          $media->src_type=6;
          $media->use_Of_file=1;
          $media->src_id=$user->id;
          $media->file_name=Str::limit($fullname,250);
          $media->alt_text=Str::limit($name,250);
          $media->file_size=$size;
          $media->file_type=1;
          $file->move(public_path($path), $img);
          $media->file_url =$fullpath;
          $media->addedby_id=auth::id();
          $media->save();

      }
    
      if($r->resetMail){
        $verifycode = mt_rand(100000,999999);
        $token = Str::random(60).$user->id;
        $user->reset_remember=$token;
        $user->verify_code=$verifycode;
        $user->save();
        
        $general=general();
        
        //**********Send Mail***************//

        if($general->mail_status && $user->email){
            try {
                Mail::send('mails.passwordResetVerify', ['user' => $user,'general'=>$general], function ($message) use ($user,$general) {
    
                    $message->from($general->mail_from_address,$general->mail_from_name);
    
                    $message->to($user->email,$user->name)
                    ->subject('Reset password verify code from '.$general->mail_from_name.".");
                });
            } catch (\Exception $e) {
                
            }
            
        }
        //**********Send Mail***************//
        Session()->flash('success','User Reset Mail Send Are Successfully Done!');
      }else{
          Session()->flash('success','User Profile Updated Are Successfully Done!');
      }
    
      $user->status=$r->status?true:false;
      $user->save();

      
      return redirect()->route('admin.usersCustomerEdit',[$user->id,'profile']);

     }else if($type=='change-password'){

          $validator = Validator::make($r->all(), [
              'old_password' => 'required|string|min:8',
              'password' => 'required|string|min:8|confirmed|different:old_password',
          ]);
         
          if($validator->fails()){
            
              return redirect()->route('admin.usersCustomerEdit',[$user->id,'change-password'])->withErrors($validator)->withInput();
          }

          if(Hash::check($r->old_password, $user->password)){
            $user->password_show=$r->password;
            $user->password=Hash::make($r->password);
            $user->update();
           
            Session()->flash('success','Your Are Successfully Done');
            return redirect()->route('admin.usersCustomerEdit',[$user->id,'change-password']);
          }else{
          Session()->flash('error','Carrent Password Are Not Match');
          return redirect()->route('admin.usersCustomerEdit',[$user->id,'change-password']);
          }

     }else{
      Session()->flash('error','Undefined route Action Please Try Agin?');
      return redirect()->route('admin.usersCustomerEdit',[$user->id,'profile']);
     }



  }

  public function usersCustomerDelete($id){

      $user=User::whereIn('status',[0,1])->find($id);

      if(!$user){
        Session()->flash('error','This User Are Not Found');
        return redirect()->route('admin.usersCustomer');
      }

      $userFiles =Media::latest()->where('src_type',6)->where('src_id',$user->id)->get();

      foreach ($userFiles as $media) {
             
          if(File::exists(public_path($media->file_url))){
                File::delete(public_path($media->file_url));
            }

          $media->delete();

      }

      $user->delete();

      Session()->flash('success','User Are Deleted Successfully Done');
      return redirect()->route('admin.usersCustomer');


    }


    public function employeeUser(Request $r){

      if ($r->isMethod('post')){
          
          $check = $r->validate([
            'name' => 'required|max:100',
            'mobile' => 'required|max:20',
          ]);
          
            $login = $r->mobile;

            $remember_me  = ( !empty( $r->remember ) )? TRUE : FALSE;
            
            if(filter_var($login, FILTER_VALIDATE_EMAIL)){
                $field = 'email';
            } else {
                $field = 'mobile';
            }
        
          $user =User::where($field,$login)->first();
          if(!$user){
            
            $password =Str::random(8);

            $user =new User;
            $user->name =$r->name;
            if($field=='email'){
              $user->email =$login; 
            }else{
              $user->mobile =$login;  
            }

            $user->password_show=$password;
            $user->password=Hash::make($password);
            $user->customer =true;
            $user->employee =true;

            $user->save();
            
            

          }else{
            
            if($user->employee==false){
              $user->employee =true;
              $user->save();
            }

          }

          

         return redirect()->route('admin.employeeUserEdit',$user->id);

      }

      $users =User::latest()->whereIn('status',[0,1])->where('employee',true)
        ->where(function($q) use($r) {

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
                $q->orWhere('email','LIKE','%'.$r->search.'%');
                $q->orWhere('mobile','LIKE','%'.$r->search.'%');
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

        })
        ->select(['id','permission_id','name','email','mobile','created_at','addedby_id','status'])
          ->paginate(25)->appends([
            'search'=>$r->search,
            'startDate'=>$r->startDate,
            'endDate'=>$r->endDate,
          ]);

      return view('admin.users.employee.users',compact('users','r'));


    }


    public function employeeUserEdit(Request $r,$id){

      $user=User::whereIn('status',[0,1])->where('employee',true)->find($id);

      if(!$user){
        Session()->flash('error','This Employee Are Not Found');
        return redirect()->route('admin.employeeUser');
      }


      if ($r->isMethod('post')){
         
          $check = $r->validate([
              'name' => 'required|max:100|unique:users,name,'.$user->id,
              'first_name' => 'nullable|max:100',
              'last_name' => 'nullable|max:100',
              'email' => 'required|max:100|unique:users,email,'.$user->id,
              'mobile' => 'required|max:20|unique:users,mobile,'.$user->id,
              'gender' => 'nullable|max:10',
              'address' => 'nullable|max:191',
              'prefecture' => 'nullable|numeric',
              'city' => 'nullable|max:191',
              'postal_code' => 'nullable|max:20',
              'designation' => 'nullable|max:200',
              //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);

          $user->name =$r->name;
          $user->first_name =$r->first_name;
          $user->last_name =$r->last_name;
          $user->mobile =$r->mobile;
          $user->email =$r->email;
          $user->gender =$r->gender;
          $user->address_line1 =$r->address;
          $user->district =$r->prefecture;
          $user->city_name =$r->city;
          $user->postal_code =$r->postal_code;
          $user->designation =$r->designation;

          ///////Image Uploard Start////////////
          if($r->hasFile('image')){
             $file=$r->image;
             $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$user->id)->first();
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
              $media->src_type=6;
              $media->use_Of_file=1;
              $media->src_id=$user->id;
              $media->file_name=Str::limit($fullname,250);
              $media->alt_text=Str::limit($name,250);
              $media->file_size=$size;
              $media->file_type=1;
              $file->move(public_path($path), $img);
              $media->file_url =$fullpath;
              $media->addedby_id=auth::id();
              $media->save();

          }

          $user->status=$r->status?true:false;
          $user->save();

          Session()->flash('success','Your Updated Are Successfully Done!');
          return redirect()->back();
      }


      return view('admin.users.employee.editUser',compact('user','r'));
    }
    
    
    public function employeeUserDelete($id){
        $user=User::whereIn('status',[0,1])->where('employee',true)->find($id);

        if(!$user){
        Session()->flash('error','This Employee Are Not Found');
        return redirect()->route('admin.employeeUser');
        }
        
        $user->employee =false;
        $user->save();
        
        Session()->flash('success','Employee Remove Successfully Done!');
        return redirect()->back();
    }


    public function supplierUsers(Request $r){

      if ($r->isMethod('post')){
          
          $check = $r->validate([
            'name' => 'required|max:100',
            'mobile' => 'required|max:20',
          ]);

          $user =User::where('mobile',$r->mobile)->first();
          if(!$user){
            
            $password =Str::random(8);

            $user =new User;
            $user->name =$r->name;
            $user->mobile =$r->mobile;
            $user->password_show=$password;
            $user->password=Hash::make($password);
            $user->customer =true;
            $user->business =true;
            $user->save();

          }else{
            
            if($user->business==false){
              $user->business =true;
              $user->save();
            }

          }

          

         return redirect()->route('admin.supplierUsersEdit',$user->id);

      }

      $users =User::latest()->whereIn('status',[0,1])->where('business',true)
        ->where(function($q) use($r) {

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
                $q->orWhere('email','LIKE','%'.$r->search.'%');
                $q->orWhere('mobile','LIKE','%'.$r->search.'%');
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

        })
        ->select(['id','permission_id','name','email','mobile','created_at','addedby_id','status'])
          ->paginate(25)->appends([
            'search'=>$r->search,
            'startDate'=>$r->startDate,
            'endDate'=>$r->endDate,
          ]);

      return view('admin.users.suppliers.users',compact('users','r'));


    }

    public function supplierUsersEdit(Request $r,$id){

      $user=User::whereIn('status',[0,1])->where('business',true)->find($id);

      if(!$user){
        Session()->flash('error','This Supplier Are Not Found');
        return redirect()->route('admin.supplierUsers');
      }


      if ($r->isMethod('post')){
          
          $check = $r->validate([
              'name' => 'required|max:100|unique:users,name,'.$user->id,
              'first_name' => 'nullable|max:100',
              'last_name' => 'nullable|max:100',
              'company_name' => 'nullable|max:100',
              'email' => 'nullable|max:100|unique:users,email,'.$user->id,
              'mobile' => 'required|max:20|unique:users,mobile,'.$user->id,
              'gender' => 'nullable|max:10',
              'address' => 'nullable|max:191',
              'prefecture' => 'nullable|numeric',
              'city' => 'nullable|max:191',
              'postal_code' => 'nullable|max:20',
              //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);

          $user->name =$r->name;
          $user->first_name =$r->first_name;
          $user->last_name =$r->last_name;
          $user->company_name =$r->company_name;
          $user->mobile =$r->mobile;
          $user->email =$r->email;
          $user->gender =$r->gender;
          $user->address_line1 =$r->address;
          $user->district =$r->prefecture;
          $user->city_name =$r->city;
          $user->postal_code =$r->postal_code;

          ///////Image Uploard Start////////////
          if($r->hasFile('image')){
             $file=$r->image;
             $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$user->id)->first();
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
              $media->src_type=6;
              $media->use_Of_file=1;
              $media->src_id=$user->id;
              $media->file_name=Str::limit($fullname,250);
              $media->alt_text=Str::limit($name,250);
              $media->file_size=$size;
              $media->file_type=1;
              $file->move(public_path($path), $img);
              $media->file_url =$fullpath;
              $media->addedby_id=auth::id();
              $media->save();

          }

          $user->status=$r->status?true:false;
          $user->save();

          Session()->flash('success','Your Updated Are Successfully Done!');
          return redirect()->back();
      }
      
      $orders =Order::where('order_type','purchase_order')->where('user_id',$user->id)->where('order_status','confirmed')->where('grand_total','>',0)->select(['id','grand_total','paid_amount','due_amount','extra_amount'])->get();
    
      $reports =array(
            'purchase_total'=>$orders->sum('grand_total'),
            'purchase_due'=>$orders->sum('due_amount'),
            'purchase_advence'=>$orders->sum('extra_amount'),
            'return_total'=>0,
            'return_due'=>0,
            'return_advence'=>0,
          );

      return view('admin.users.suppliers.editUser',compact('user','reports','r'));
    }
    
    public function supplierUsersDelete($id){
        $user=User::whereIn('status',[0,1])->where('business',true)->find($id);

          if(!$user){
            Session()->flash('error','This Supplier Are Not Found');
            return redirect()->route('admin.supplierUsers');
          }
        $user->business =false;
        $user->save();
        
        Session()->flash('success','Supplier Remove Successfully Done!');
        return redirect()->back();
          
    }


    public function subscribes(Request $r){

      // Filter Action Start
        if($r->action){
          if($r->checkid){

          PostExtra::latest()->where('type',2)->whereIn('id',$r->checkid)->delete();

          Session()->flash('success','Action Successfully Completed!');

          }else{
            Session()->flash('info','Please Need To Select Minimum One Post');
          }

          return redirect()->back();
        }

        //Filter Action End

      $subscribes =PostExtra::where('type',1)
      ->where(function($q) use ($r){

        if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
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

      })
      ->select(['id','name','created_at'])
      ->paginate(50)->appends([
        'search'=>$r->search,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

      return view('admin.users.subscribes.subscribeAll',compact('subscribes','r'));
    }


  public function userRoles(Request $r){
    $allPer = empty(json_decode(Auth::user()->permission->permission, true)['services']['all']);

    $roles =Permission::latest()
    ->where('status','active')
    ->where(function($q) use($r,$allPer) {

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

       // Check Permission
        if($allPer){
         $q->where('addedby_id',auth::id()); 
        }

    })
    ->select(['id','name','created_at','addedby_id','status'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

    return view('admin.users.roles.userRoles',compact('roles','r'));
  }


  public function userRoleAction(Request $r,$action,$id){
      
      $user =Auth::user();

      if($action!='add'){
      $role=Permission::find($id);
        if(!$role){
          Session()->flash('error','This Role Are Not Found');
          return redirect()->route('admin.userRoles');
        }

      }

      if($action=='add'){
        
        //Role Added Start

          $role  =Permission::where('addedby_id',$user->id)->where('status','temp')->first();

          if(!$role){
            $role = new Permission();
            $role->status='temp';
            $role->addedby_id=$user->id;
            $role->save();
          }else{
            $role->created_at=Carbon::now();
            $role->save();
          }
          
          return redirect()->route('admin.userRoleAction',['action'=>'edit','id'=>$role->id]);

      }elseif($action=='edit'){

        //Role Edit Start

        return view('admin.users.roles.userRoleEdit',compact('role'));

      }elseif($action=='update'){

        //Role Edit Update

        $check = $r->validate([
            'name' => 'required|max:100',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return back();
        }
        $role->name =$r->name;
        if($role->id==1){
        $role->permission =$r->permission;
        }else{
          $role->permission =$r->permission;  
        }
        $role->status ='active';
        $role->save();
        Session()->flash('success','Role Updated Are Successfully Done!');
        return redirect()->route('admin.userRoleAction',['action'=>'edit','id'=>$role->id]);


      }elseif($action=='delete'){

        //Role Edit Delete

        $role->delete();

        Session()->flash('success','Role Deleted Are Successfully Done!');
        $role->delete();

        return redirect()->route('admin.userRoles');

      }else{
        Session()->flash('error','Somthing Worng Action Please Try Again.');
        return redirect()->route('admin.userRoles');
      }


  }

  // User Management Function End


  // Setting Function Start
  public function setting($type){

    $general =General::first();
    if($type=='general'){
      return view('admin.setting.general',compact('general','type'));
    }else if($type=='mail'){
      return view('admin.setting.mail',compact('general','type'));
    }else if($type=='sms'){
      return view('admin.setting.sms',compact('general','type'));
    }else if($type=='social'){
      return view('admin.setting.social',compact('general','type'));
    }else if($type=='document'){
      return view('admin.setting.document',compact('general','type'));
    }else if($type=='logo'){

      if(File::exists(public_path($general->logo))){
            File::delete(public_path($general->logo));
      }
      $general->logo=null;
      $general->save();

      Session()->flash('success','Logo Deleted Are Successfully Done!');
      return redirect()->back();
    }else if($type=='favicon'){
       if(File::exists(public_path($general->favicon))){
            File::delete(public_path($general->favicon));
      }
      $general->favicon=null;
      $general->save();

      Session()->flash('success','Logo Deleted Are Successfully Done!');
      return redirect()->back();
    }else if($type=='banner'){
       if(File::exists(public_path($general->banner))){
            File::delete(public_path($general->banner));
      }
      $general->banner=null;
      $general->save();

      Session()->flash('success','Banner Deleted Are Successfully Done!');
      return redirect()->back();
    }else{
      return redirect()->route('admin.setting','general','type');
    }

  }




  public function setitngUpdate(Request $r,$type){


    $general =General::first();

    if($type=='general'){

        $check = $r->validate([
            'title' => 'nullable|max:100',
            'subtitle' => 'nullable|max:200',
            'mobile' => 'nullable|max:250',
            'email' => 'nullable|max:250',
            'currency' => 'nullable|max:10',
            'website' => 'nullable|max:100',
            'meta_author' => 'nullable|max:100',
            'meta_title' => 'nullable|max:200',
            'meta_description' => 'nullable|max:200',
            //'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $general->title=$r->title;
        $general->subtitle=$r->subtitle;
        $general->mobile=$r->mobile;
        $general->email=$r->email;
        $general->address_one=$r->address_one;
        $general->address_two=$r->address_two;
        $general->currency=$r->currency;
        $general->currency_decimal=$r->currency_decimal;
        $general->currency_position=$r->currency_position;
        $general->website=$r->website;
        $general->meta_author=$r->meta_author;
        $general->meta_title=$r->meta_title;
        $general->meta_keyword=$r->meta_keyword;
        $general->meta_description=$r->meta_description;
        $general->script_head=$r->script_head;
        $general->script_body=$r->script_body;
        $general->custom_css=$r->custom_css;
        $general->custom_js=$r->custom_js;
        $general->copyright_text=$r->copyright_text;
        

        ///////Image Uploard Start////////////

        if($r->hasFile('logo')){

            $file=$r->logo;

            if(File::exists(public_path($general->logo))){
                  File::delete(public_path($general->logo));
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

            $file->move(public_path($path), $img);
            $general->logo =$fullpath;

        }

         ///////Image Uploard Start////////////

        if($r->hasFile('favicon')){

            $file=$r->favicon;

            if(File::exists(public_path($general->favicon))){
                  File::delete(public_path($general->favicon));
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
            
            $file->move(public_path($path), $img);
            $general->favicon =$fullpath;

        }
        
        if($r->hasFile('banner')){

            $file=$r->banner;

            if(File::exists(public_path($general->banner))){
                  File::delete(public_path($general->banner));
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
            
            $file->move(public_path($path), $img);
            $general->banner =$fullpath;

        }
        $general->commingsoon_mode=$r->commingsoon_mode?true:false;
        $general->save();

        Session()->flash('success','General Updated Are Successfully Done!');

    }


    if($type=='mail'){

      $check = $r->validate([
            'mail_from_address' => 'nullable|max:100',
            'mail_from_name' => 'nullable|max:100',
            'mail_driver' => 'nullable|max:100',
            'mail_host' => 'nullable|max:100',
            'mail_port' => 'nullable|max:100',
            'mail_encryption' => 'nullable|max:100',
            'mail_username' => 'nullable|max:100',
            'mail_password' => 'nullable|max:100',
        ]);

      $general->mail_from_address=$r->mail_from_address;
      $general->mail_from_name=$r->mail_from_name;
      $general->mail_driver=$r->mail_driver;
      $general->mail_host=$r->mail_host;
      $general->mail_port=$r->mail_port;
      $general->mail_encryption=$r->mail_encryption;
      $general->mail_username=$r->mail_username;
      $general->mail_password=$r->mail_password;
      $general->mail_status=$r->mail_status?true:false;
      $general->save();

      Session()->flash('success','Mail Updated Are Successfully Done!');

    }

    if($type=='sms'){

      $check = $r->validate([
            'sms_type' => 'nullable|max:50',
            'sms_senderid' => 'nullable|max:50',
            'sms_url_nonmasking' => 'nullable|max:200',
            'sms_url_masking' => 'nullable|max:200',
            'sms_username' => 'nullable|max:50',
            'sms_password' => 'nullable|max:50',
        ]);

      $general->sms_type=$r->sms_type;
      $general->sms_senderid=$r->sms_senderid;
      $general->sms_url_nonmasking=$r->sms_url_nonmasking;
      $general->sms_url_masking=$r->sms_url_masking;
      $general->sms_username=$r->sms_username;
      $general->sms_password=$r->sms_password;
      $general->admin_numbers=$r->admin_numbers;
      $general->sms_status=$r->sms_status?true:false;
      $general->save();

      Session()->flash('success','SMS Updated Are Successfully Done!');

    }

    if($type=='social'){
      

      $check = $r->validate([
            'facebook_link' => 'nullable|max:200',
            'twitter_link' => 'nullable|max:200',
            'instagram_link' => 'nullable|max:200',
            'linkedin_link' => 'nullable|max:200',
            'pinterest_link' => 'nullable|max:200',
            'youtube_link' => 'nullable|max:200',
            'fb_app_id' => 'nullable|max:100',
            'fb_app_secret' => 'nullable|max:100',
            'fb_app_redirect_url' => 'nullable|max:200',
            'google_client_id' => 'nullable|max:100',
            'google_client_secret' => 'nullable|max:100',
            'google_client_redirect_url' => 'nullable|max:200',
            'tw_app_id' => 'nullable|max:100',
            'tw_app_secret' => 'nullable|max:100',
            'tw_app_redirect_url' => 'nullable|max:200',
        ]);

        $general->facebook_link=$r->facebook_link;
        $general->twitter_link=$r->twitter_link;
        $general->instagram_link=$r->instagram_link;
        $general->linkedin_link=$r->linkedin_link;
        $general->pinterest_link=$r->pinterest_link;
        $general->youtube_link=$r->youtube_link;
        $general->fb_app_id=$r->fb_app_id;
        $general->fb_app_secret=$r->fb_app_secret;
        $general->fb_app_redirect_url=$r->fb_app_redirect_url;
        $general->google_client_id=$r->google_client_id;
        $general->google_client_secret=$r->google_client_secret;
        $general->google_client_redirect_url=$r->google_client_redirect_url;
        $general->tw_app_id=$r->tw_app_id;
        $general->tw_app_secret=$r->tw_app_secret;
        $general->tw_app_redirect_url=$r->tw_app_redirect_url;
        $general->save();

        Session()->flash('success','Advance Updated Are Successfully Done!');

    }

    
    return redirect()->route('admin.setting',$type);


  }

  // Setting Function End
    


}
