<?php

namespace App\Http\Controllers\Admin;


use Auth;
use Str;
use File;
use Session;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attribute;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenusController extends Controller
{
      //Menus Route

    public function menus(){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['menus']['all']);

      $menus =Attribute::latest()->where('type',8)->where('parent_id',null)->where('status','<>','temp')
      ->where(function($q) use ($allPer) {
          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }
      })
      ->select(['id','name','location','addedby_id','status'])
      ->paginate(100);
      return view('admin.menus.menusAll',compact('menus'));
      
    }

    public function menusCreate(){
      $menu =Attribute::latest()->where('type',8)->where('parent_id',null)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$menu){
      $menu =new Attribute();
      $menu->status ='temp';
      $menu->type =8;
      $menu->addedby_id =Auth::id();
      $menu->save();
      }else{
      $menu->created_at =Carbon::now();
      $menu->save();
      }
      
      return redirect()->route('admin.menusEdit',$menu->id);
    }

    public function menusEdit($id){
      $menu =Attribute::where('type',8)->find($id);
      
      if(!$menu){
        Session()->flash('error','This Menu Are Not Found');
        return redirect()->route('admin.menus');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['menus']['all']);
        if($allPer && $role->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.menus');
        }

      $pages =Post::latest()->where('type',0)->where('status','<>','temp')->get();
      $blogCategories =Attribute::latest()->where('type',6)->where('parent_id',null)->where('status','<>','temp')->get();
      $productCategories =Attribute::latest()->where('type',0)->where('parent_id',null)->where('status','<>','temp')->get();
      $parent =Attribute::where('type',8)->find($menu->category_id);
      if(!$parent){
        $parent =$menu;
      }
      
      return view('admin.menus.menuEdit',compact('menu','pages','blogCategories','productCategories','parent'));
    }

    public function menusUpdate(Request $r,$id){
      $menu =Attribute::where('type',8)->find($id);
      if(!$menu){
        Session()->flash('error','This Menu Are Not Found');
        return redirect()->route('admin.menus');
      }
      
      $parent =Attribute::where('type',8)->find($menu->category_id);
      if(!$parent){
        $parent =$menu;
      }

      if($r->location){
        $location =Attribute::where('location', $r->location)->whereNotIn('id',[$parent->id])->get();
        if($location->count() > 0){
            Session::flash('error','"'.$r->location.'" Already Use');
            return back();
        }
      }

      $parent->name=$r->name;
      $parent->location=$r->location;
      $parent->status =$r->status?'active':'inactive';
      $parent->editedby_id =Auth::id();
      $parent->save();

      if($r->menuids){

        for ($i =0; $i < count($r->menuids); $i++){
            if($item =Attribute::find($r->menuids[$i])){
               $item->view=$i;
               $item->save();
            }
        }

      }

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function menusDelete($id){
       $menu =Attribute::where('type',8)->find($id);
      if(!$menu){
        Session()->flash('error','This Menu Are Not Found');
        return redirect()->route('admin.menus');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['menus']['all']);
        if($allPer && $role->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.menus');
        }

      $items=Attribute::where('type',8)->where('category_id',$menu->id)->where('status','<>','temp')->get();

      foreach ($items as $item) {
        
          //Menu  Media File Delete
          $medies =Media::where('src_type',3)->where('src_id',$item->id)->get();
          foreach ($medies as  $media) {
              if(File::exists(public_path($media->file_url))){
                  File::delete(public_path($media->file_url));
              }
              $media->delete();
          }

        $item->delete();  

      }

      $menu->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();


    }




    public function menusItemsAjax(Request $request,$id){
      $menu =Menu::find($id);
      if(!$menu){
        Session()->flash('error','This Menu Are Not Found');
        return redirect()->route('admin.menus');
      }
      
      if($request->ajax())
        {
            $items=Menu::where('parent_id',$menu->id)->where('status','<>','temp')->orderBy('drag', 'asc')->get();
            return Response()->json([
                'success' => true,
                'view' => View('admin.menus.includes.menusItemsAll',[
                    'items'=>$items,
                    'menu'=>$menu
                ])->render()
            ]);
        }

        return back();
    }

    public function menusItemsPost(Request $r,$id){
        
        $menu =Attribute::where('type',8)->find($id);
        if(!$menu){
          Session()->flash('error','This Menu Are Not Found');
          return redirect()->route('admin.menus');
        }

      
        if($r->menuname){
            $check = $r->validate([
              'menuname' => 'required|max:191',
              'menulink' => 'required|max:300',
              'parent' => 'required|numeric',
            ]);
        }elseif($r->pages){
          $check = $r->validate([
              'pages.*' => 'required|numeric',
              'parent' => 'required|numeric',
            ]);
        }elseif($r->blogCategories){
          $check = $r->validate([
              'blogCategories.*' => 'required|numeric',
              'parent' => 'required|numeric',
            ]);
        }elseif($r->productCategories){
          $check = $r->validate([
              'productCategories.*' => 'required|numeric',
              'parent' => 'required|numeric',
            ]);
        }else{
            Session::flash('error','Need To validatation');
            return back();
        }

        // menu_type == 0=Custom Link, 1=Pages, 2=Post Categories, 3=Service Categories;

        if($r->menuname){
        $items =new  Attribute();
        $items->type=8;
        $items->parent_id=$menu->id;
        $items->category_id=$r->parent;
        $items->name=$r->menuname;
        $items->slug=$r->menulink;
        $items->menu_type=0;
        $items->status='active';
        $items->addedby_id=auth::id();
        $items->save();
        }

        if($r->pages){
            for ($i =0; $i < count($r->pages); $i++){

              $items =new  Attribute();
              $items->parent_id=$menu->id;
              $items->category_id=$r->parent;
              $items->type=8;
              $items->src_id= $r->pages[$i];
              $items->menu_type=1;
              $items->status='active';
              $items->addedby_id=auth::id();
              $items->save();

          }
        }

        if($r->blogCategories){
            for ($i =0; $i < count($r->blogCategories); $i++){

              $items =new  Attribute();
              $items->parent_id=$menu->id;
              $items->category_id=$r->parent;
              $items->type=8;
              $items->src_id= $r->blogCategories[$i];
              $items->menu_type=2;
              $items->status='active';
              $items->addedby_id=auth::id();
              $items->save();

          }
        }

        if($r->productCategories){
            for ($i =0; $i < count($r->productCategories); $i++){

              $items =new  Attribute();
              $items->parent_id=$menu->id;
              $items->category_id=$r->parent;
              $items->type=8;
              $items->src_id= $r->productCategories[$i];
              $items->menu_type=3;
              $items->status='active';
              $items->addedby_id=auth::id();
              $items->save();

          }
        }


    Session()->flash('success','You Are Successfully Done:)');
    return redirect()->back(); 

    }

    public function menusItemsEdit($id){
      $menu =Attribute::where('type',8)->find($id);
      if(!$menu){
        Session()->flash('error','This Menu Item Are Not Found');
        return redirect()->route('admin.menus');
      }
      return view('admin.menus.menuItemEdit',compact('menu'));
    }

    public function menusItemsUpdate(Request $r,$id){
      $item =Attribute::where('type',8)->find($id);
      if(!$item){
        Session()->flash('error','This Menu Item Are Not Found');
        return redirect()->route('admin.menus');
      }

      if($item->menu_type==0){
        $check = $r->validate([
            'name' => 'required|max:191',
            'link' => 'nullable|max:300',
        ]);

      }

        $check = $r->validate([
            'icon' => 'nullable|max:50',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        
       $item->name=$r->name;
       $item->slug=$r->link;

        ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',3)->where('use_Of_file',1)->where('src_id',$item->id)->first();
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
          $media->src_id=$item->id;
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

      
      $item->icon=$r->icon;
      $item->target =$r->target?true:false;
      $item->save();

      Session()->flash('success','You Are Successfully Done:)');
      return redirect()->back();

    }


    public function menusItemsDelete($id){
      $item =Attribute::where('type',8)->find($id);
      if(!$item){
        Session()->flash('error','This Menu Item Are Not Found');
        return redirect()->route('admin.menus');
      }

       //Menus sub Menu replace

        foreach($item->subMenus as $par){
          $par->parent_id=$item->parent_id;
          $par->save();
        }

      //Menu  Media File Delete
        $medies =Media::where('src_type',3)->where('src_id',$item->id)->get();
        foreach ($medies as  $media) {
            if(File::exists(public_path($media->file_url))){
                File::delete(public_path($media->file_url));
            }
            $media->delete();
        }

      $item->delete();
      Session()->flash('success','You Are Successfully Done:)');
      return redirect()->back();

    }

    //Menus Route End











}
