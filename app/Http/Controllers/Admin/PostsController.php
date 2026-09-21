<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use File;
use DB;
use Session;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\PostExtra;
use App\Models\Menu;
use App\Models\Review;
use App\Models\General;
use App\Models\Country;
use App\Models\Media;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    
    

    //Post Function
    public function posts(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['posts']['all']);
    
    // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Post::latest()->where('type',1)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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

              $data->postCtgs()->delete();
              $data->postTags()->delete();
              $data->postComments()->delete();
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


      $posts=Post::latest()->where('type',1)
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
      ->select(['id','name','slug','created_at','addedby_id','status'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'status'=>$r->status,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);
      
      //Total Count Results
      $totals = DB::table('posts')
      ->where('type',1)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
      ->first();


      return view('admin.posts.postsAll',compact('posts','r','totals'));
    }

    public function postsCreate(){
        
      $post =new Post();
      $post->type =1;
      $post->status ='temp';
      $post->addedby_id =Auth::id();
      $post->save();

      return redirect()->route('admin.postsEdit',$post->id);
    }

    public function postsEdit($id){
      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['posts']['all']);
        if($allPer && $post->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.posts');
        }

      $categories =Attribute::where('type',6)->where('status','<>','temp')->where('parent_id',null)->get();
      $tags =Attribute::where('type',7)->where('status','<>','temp')->where('parent_id',null)->get();
      
      return view('admin.posts.postEdit',compact('post','categories','tags'));
    }


    public function postsUpdate(Request $r,$id){

      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:250',
            'catagoryid.*' => 'nullable|numeric',
            'tags.*' => 'nullable|numeric',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

      $post->name=$r->name;
      $post->short_description=$r->short_description;
      $post->description=$r->description;
      $post->seo_title=$r->seo_title;
      $post->seo_desc=$r->seo_desc;
      $post->seo_keyword=$r->seo_keyword;

      ///////Image Uploard Start////////////
      if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',1)->where('use_Of_file',1)->where('src_id',$post->id)->first();
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
          $media->src_id=$post->id;
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
         $media =Media::latest()->where('src_type',1)->where('use_Of_file',2)->where('src_id',$post->id)->first();

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
          $media->src_id=$post->id;
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
        $post->slug=$post->id;
       }else{
        if(Post::where('type',1)->where('slug',$slug)->whereNotIn('id',[$post->id])->count() >0){
        $post->slug=$slug.'-'.$post->id;
        }else{
        $post->slug=$slug;
        }
      }
      if($r->created_at){
        $post->created_at =$r->created_at;
      }
      $post->status =$r->status?'active':'inactive';
      $post->fetured =$r->fetured?1:0;
      $post->editedby_id =Auth::id();
      $post->save();



      //Category posts
      if($r->categoryid){

      $post->postCtgs()->whereNotIn('reff_id',$r->categoryid)->delete();

       for ($i=0; $i < count($r->categoryid); $i++) {

        $ctg = $post->postCtgs()->where('reff_id',$r->categoryid[$i])->first();

        if($ctg){}else{
        $ctg =new PostAttribute();
        $ctg->src_id=$post->id;
        $ctg->reff_id=$r->categoryid[$i];
        $ctg->type=1;
        }
        $ctg->drag=$i;
        $ctg->save();
       }

     }else{
        $post->postCtgs()->delete();
       }
       
      //Tag posts
        if($r->tags){

          $post->postTags()->whereNotIn('reff_id',$r->tags)->delete();

          for ($i=0; $i < count($r->tags); $i++) {
            $tag = $post->postTags()->where('reff_id',$r->tags[$i])->first();

              if($tag){}else{
              $tag =new PostAttribute();
              $tag->type=2;
              $tag->src_id=$post->id;
              $tag->reff_id=$r->tags[$i];
              }
              $tag->save();
         }
       }else{
        $post->postTags()->delete();
       }

       ///////Search Key Start////////
        $key='';
        $key.=$post->name;
        //Post Category Name
        foreach($post->postCtgs as $postctg){
            if($ctg=Attribute::where('type',6)->find($postctg->catagory_id)){
                $key.=' '.$ctg->name;
            }
        }
        //Post Tag Name
        foreach($post->postTags as $posttag){
            if($tag=Attribute::where('type',7)->find($posttag->catagory_id)){
                $key.=' '.$tag->name;
            }
        }
        $post->search_key=Str::limit($key,450);
        
        $post->save();
        ///////Search Key End////////

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

    }

    public function postsDelete($id){
      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['posts']['all']);
        if($allPer && $post->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.posts');
        }

      $medias =Media::latest()->where('src_type',1)->where('src_id',$post->id)->get();
        foreach($medias as $media){
          if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
          }
          $media->delete();
        }

      $post->postCtgs()->delete();
      $post->postTags()->delete();
      $post->postComments()->delete();
      $post->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }


    //Post Function End


    //Post Comments Function Start

    public function postsCommentsAll(Request $r){

      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Review::latest()->where('type',1)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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
              if($data->post){
                $post =$data->post;
                $post->comments-=1;
                $post->save();
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

      $comments =Review::latest()->where('type',1)->where('status','<>','temp')
      ->where(function($q) use ($r) {

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
                $q->orWhere('email','LIKE','%'.$r->search.'%');
                $q->orWhere('website','LIKE','%'.$r->search.'%');
            }

      })
      ->select(['id','src_id','parent_id','name','email','title','website','content','type','status','addedby_id','created_at'])
      ->paginate(25)->appends([
        'search'=>$r->search,
      ]);
      return view('admin.posts.comments.postsCommentsAll',compact('comments','r'));
    }

    public function postsComments(Request $r,$id){
      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Review::latest()->where('type',1)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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
              if($data->post){
                $post =$data->post;
                $post->comments-=1;
                $post->save();
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
      
      $comments =Review::latest()->where('type',1)->where('src_id',$post->id)->where('status','<>','temp')
      ->where(function($q) use ($r) {

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
                $q->orWhere('email','LIKE','%'.$r->search.'%');
                $q->orWhere('website','LIKE','%'.$r->search.'%');
            }

      })
      ->select(['id','src_id','parent_id','name','email','title','website','content','type','status','addedby_id','created_at'])
      ->paginate(25)->appends([
        'search'=>$r->search,
      ]);;
      
      return view('admin.posts.comments.postsComments',compact('comments','post','r'));
    }

    public function postsCommentsCreate($id){
      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      $comment =Review::where('type',1)->where('addedby_id',Auth::id())->where('src_id',$post->id)->where('status','temp')->first();
      if(!$comment){
      $comment =new Review();
      $comment->type =1;
      $comment->src_id =$post->id;
      $comment->addedby_id =Auth::id();
      $comment->save();
      }else{
      $comment->created_at =Carbon::now();
      $comment->save();
      }

      return redirect()->route('admin.postsCommentsEdit',$comment->id);
    }

    public function postsCommentsEdit($id){
      $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsComments');
      }
      return view('admin.posts.comments.CommentEdit',compact('comment'));
    }

    public function postsCommentsUpdate(Request $r,$id){
      $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsComments');
      }
      if($comment->status=='temp' && $comment->post){
        $post =$comment->post;
        $post->comments+=1;
        $post->save();
      }
      $comment->name=$r->name;
      $comment->email=$r->email;
      $comment->website=$r->website;
      $comment->content=$r->content;
      $comment->status =$r->status?'active':'inactive';
      $comment->fetured =$r->fetured?true:false;
      $comment->editedby_id=Auth::id();
      $comment->save();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function postsCommentsStatus($id){
       $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsComments');
      }
      if($comment->status=='active'){
      $comment->status='inactive';
      }else{
      $comment->status='active';
      }
      
      $comment->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    public function postsCommentsReplay($id){
      $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsComments');
      }
      return view('admin.posts.comments.replay',compact('comment'));
    }

    public function postsCommentsReplayPost(Request $r,$id){

      $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsComments');
      }

      if($comment->post){
        $post =$comment->post;
        $post->comments+=1;
        $post->save();
      }

      $replay =new Review();
      $replay->src_id=$comment->src_id;
      $replay->parent_id=$comment->id;
      $replay->name=$r->name;
      $replay->email=$r->email;
      $replay->website=$r->website;
      $replay->content=$r->content;
      $replay->status =$r->status?'active':'inactive';
      $replay->fetured =$r->fetured?true:false;
      $replay->addedby_id=Auth::id();
      $replay->type=1;
      $replay->save();



      Session()->flash('success','Your Are Successfully Done');
      return redirect()->route('admin.postsCommentsReplay',$comment->id);

    }

    public function postsCommentsDelete($id){
      $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsComments');
      }
      if($comment->post){
          $post =$comment->post;
          $post->comments-=1;
          $post->save();
        }
      $comment->delete();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    //Post Comments Function End

    //Post Category Function
    public function postsCategories(Request $r){

    $allPer = empty(json_decode(Auth::user()->permission->permission, true)['postsCtg']['all']);

    // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::where('type',6)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

    //Filter Action End

    $categories =Attribute::latest()->where('type',6)->where('status','<>','temp')
    ->where(function($q) use ($r,$allPer) {

        if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
        }

        // Check Permission
        if($allPer){
         $q->where('addedby_id',auth::id()); 
        }

    })
    ->select(['id','name','parent_id','status','addedby_id','created_at'])
    ->paginate(25)->appends([
        'search'=>$r->search,
      ]);
      
    return view('admin.posts.category.postsCategories',compact('categories','r'));

    }

    public function postsCategoriesCreate(){

      $category =Attribute::latest()->where('type',6)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$category){
      $category =new Attribute();
      $category->type =6;
      $category->status ='temp';
      $category->addedby_id =Auth::id();
      $category->save();
      }else{
      $category->created_at =Carbon::now();
      $category->save();
      }
      return redirect()->route('admin.postsCategoriesEdit',$category->id);

    }

    public function postsCategoriesEdit($id){
      $category =Attribute::where('type',6)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.postsCategories');
      }


      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['postsCtg']['all']);
        if($allPer && $category->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.postsCategories');
        }

      $parents =Attribute::where('type',6)->where('status','<>','temp')->where('parent_id',null)->get();

      return view('admin.posts.category.postCategoryEdit',compact('category','parents'));
    }

    public function postsCategoriesUpdate(Request $r,$id){
      $category =Attribute::where('type',6)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.postsCategories');
      }

       $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:200',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category->name=$r->name;
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
        if(Attribute::where('type',6)->where('slug',$slug)->whereNotIn('id',[$category->id])->count() >0){
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

    public function postsCategoriesDelete($id){

      $category =Attribute::where('type',6)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.postsCategories');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['postsCtg']['all']);
        if($allPer && $category->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.postsCategories');
        }

      //Category Media File Delete
      $medias =Media::latest()->where('src_type',3)->where('src_id',$category->id)->get();
        foreach($medias as $media){
          if(File::exists(public_path($media->file_url))){
            File::delete(public_path($media->file_url));
          }
          $media->delete();
        }

        //Post Category sub Category replace
        foreach($category->subctgs as $subctg){
          $subctg->parent_id=$category->parent_id;
          $subctg->save();
        }
        
        $category->delete();

       Session()->flash('success','Your Are Successfully Done');
       return redirect()->back();

    }

    //Post Category Function End

    //Post Tags Function
    public function postsTags(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['postsTag']['all']);

      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::where('type',7)->where('status','<>','temp')->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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

    $tags =Attribute::latest()->where('type',7)->where('status','<>','temp')
    ->where(function($q) use ($r,$allPer) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }

        // Check Permission
        if($allPer){
         $q->where('addedby_id',auth::id()); 
        }

    })
    ->select(['id','name','parent_id','status','addedby_id','created_at'])
    ->paginate(25)->appends([
        'search'=>$r->search,
      ]);
      
      return view('admin.posts.tags.postsTags',compact('tags','r'));

    }

    public function postsTagsCreate(){
      $tag =Attribute::latest()->where('type',7)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$tag){
      $tag =new Attribute();
      $tag->type =7;
      $tag->status ='temp';
      $tag->addedby_id =Auth::id();
      $tag->save();
      }else{
      $tag->created_at =Carbon::now();
      $tag->save();
      }
      return redirect()->route('admin.postsTagsEdit',$tag->id);
    }

    public function postsTagsEdit($id){
      $tag =Attribute::where('type',7)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.postsTags');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['postsTag']['all']);
        if($allPer && $tag->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.postsTags');
        }

      return view('admin.posts.tags.postsTagsEdit',compact('tag'));
    }

    public function postsTagsUpdate(Request $r,$id){
      $tag =Attribute::where('type',7)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.postsTags');
      }

       $check = $r->validate([
            'name' => 'required|max:191',
        ]);

        $checkTag =Attribute::where('type',7)->whereNotIn('id',[$tag->id])->where('name',$r->name)->first();

        if($checkTag){
          Session::flash('error','Tag Name Can not use Dublicate!');
            return back();
        }

        $tag->name=$r->name;
        $tag->description=$r->description;
        $slug =Str::slug($r->name);
       if($slug==null){
        $tag->slug=$tag->id;
       }else{
        if(Attribute::where('type',7)->where('slug',$slug)->whereNotIn('id',[$tag->id])->count() >0){
        $tag->slug=$slug.'-'.$tag->id;
        }else{
        $tag->slug=$slug;
        }
       }
      $tag->status =$r->status?'active':'inactive';
      $tag->fetured =$r->fetured?1:0;
      $tag->editedby_id =Auth::id();
      $tag->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function postsTagsDelete($id){
      $tag =Attribute::where('type',7)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.postsTags');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['postsTag']['all']);
        if($allPer && $tag->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.postsTags');
        }

      $tag->delete();

      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    //Post Tags Function End
}
