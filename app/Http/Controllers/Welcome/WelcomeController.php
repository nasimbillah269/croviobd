<?php

namespace App\Http\Controllers\Welcome;


use Image;
use Http;
use PDF;
use Auth;
use Hash;
use Session;
use Mail;
use Cookie;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\General;
use App\Models\Post;
use App\Models\PostExtra;
use App\Models\User;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Review;
use App\Mail\ContactMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WelcomeController extends Controller
{

    public function __construct()
    {
      
      $this->middleware('cart');
      
      function isMobileDevice() {
          return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo 
      |fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i" 
      , $_SERVER["HTTP_USER_AGENT"]); 
      }

      if(isMobileDevice())
      {
          $this->device =General::first()->theme.'.';
      }
      else
      {
          $this->device =General::first()->theme.'.';
      }

    }
  
    
      public function geo_filter($id){
    
      $datas=Country::where('parent_id',$id)->get();
    
      $geoData =View('geofilter',compact('datas'))->render();
    
       return Response()->json([
              'success' => true,
              'geoData' => $geoData,
            ]);
      }

    public function payment_filter($id){

      $datas=Attribute::where('parent_id',$id)->get();

      $paymentData =View('paymentfilter',compact('datas'))->render();

       return Response()->json([
              'success' => true,
              'paymentData' => $paymentData,
            ]);
    }


    public function index(Request $r){
         
        
        $latestProducts =Post::latest()
        ->where('type',2)
        ->where('status','active')
        ->where('stock_status',true)
        //->where('stock_out_limit','>',1)
        // ->where('quantity','>',0)
        ->paginate(12);
        
        if ($r->ajax()) {
            return response()->json([
                'html' => view($this->device.'products.ajaxProductLoad', [
                    'latestProducts' => $latestProducts
                ])->render(),
                'hasMore' => $latestProducts->hasMorePages()
            ]);
        }
        $feturedProducts =Post::latest()->where('type',2)->where('status','active')->where('fetured',true)
        ->where('stock_status',true)
        //->where('stock_out_limit','>',1)
        // ->where('quantity','>',0)
        ->paginate(12);
        
        $homeDatas =PostExtra::where('type',4)->where('parent_id',null)->where('status','active')->orderBy('drag','asc')->get();
        
        
    	return view($this->device.'index',compact('feturedProducts','latestProducts','homeDatas'));
        //return view('index');
    }
    
    
      public function promotion(Request $r,$id){

        $homeData =PostExtra::where('type',4)->where('parent_id',null)->where('status','active')->find($id);
        if(!$homeData){
            return abort(404);
        }
        $products = $homeData->productsList()->paginate(60);
   
        
    	return view($this->device.'promotion',compact('homeData', 'products'));
        //return view('index');
    }
    

    public function productCategory(Request $request,$slug){
        
        $category =Attribute::latest()->where('type',0)->where('slug',$slug)->first();
        if(!$category){
            return abort('404');
        }
    
    
        $products = Post::whereHas('ctgProducts', function($q) use($category) {
            $q->where('reff_id', $category->id);
        })
        ->where(function($qq){
            $qq->where('status','active')
               ->where('stock_status', true);
        })
        ->whereDate('created_at', '<=', now());
    
        // --- Apply Price Filter ---
        if($request->filled('min_price')) {
            $products->where('final_price', '>=', $request->min_price);
        }
        if($request->filled('max_price')) {
            $products->where('final_price', '<=', $request->max_price);
        }
    
        // --- Apply Offer Filter ---
        if($request->filled('offer')) {
            if($request->offer == 'best_sale') {
                $products->withCount('salesAll')->orderBy('sales_all_count','desc');
            }
            elseif($request->offer == 'discount') {
                $products->whereColumn('regular_price', '>', 'final_price');
            }
        }
    
        // --- Apply Sorting ---
        if($request->filled('short_by')) {
            switch($request->short_by) {
                case 'high_to_low':
                    $products->orderBy('final_price', 'desc');
                    break;
                case 'low_to_high':
                    $products->orderBy('final_price', 'asc');
                    break;
                case 'best_selling':
                    $products->where('fetured',true);
                    break;
                case 'all':
                default:
                    $products->latest(); // default order
            }
        } else {
            $products->latest(); // default order
        }
        $topPrice = $products->max('final_price');
        // --- Select Columns ---
        $products = $products->paginate(12);
        
        if($request->ajax()){
            
            return response()->json([
                'html' => view($this->device.'products.ajaxCategoryProduct', [
                    'products' => $products
                ])->render(),
                'hasMore' => $products->hasMorePages(),
                'total' => $products->total(),
                'page_total' => $products->lastPage()
            ]);
        }
        
  
        return view($this->device.'products.category',compact('category','products','topPrice'));
      $products = Post::whereHas('ctgProducts',function($q) use($category){
        $q->where('reff_id',$category->id);
      })
      ->where(function($qq){
        $qq->where('status','active')
        ->where('stock_status',true);
        //->where('stock_out_limit','>',1)
        // ->where('quantity','>',0);
      })
      ->select(['id','name','final_price','stock_status','quantity','stock_out_limit','weight_unit','weight_amount','slug','addedby_id','created_at','regular_price','discount','discount_type'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(12);
      
      

      return view($this->device.'products.category',compact('category','products'));
    }

    public function productView(Request $r,$slug){
      $product = Post::latest()
      ->where('type',2)
      ->where('status','active')
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->where('slug',$slug)->first();
      
      if(!$product){
        return abort('404');
      }
    

    $category = $product->ctgProducts->pluck('reff_id');
    
      //return response('Set cookie');
      // return Cookie::get('name');
    
      $relatedProducts = Post::where('type',2)->whereHas('ctgProducts',function($q) use($category){
        $q->whereIn('reff_id',$category);
      })->where(function($qq){
        
        $qq->where('status','active')
        ->where('stock_status',true)
        //->where('stock_out_limit','>',1)
        ->where('quantity','>',0);
        
      })
      ->whereNotIn('id',[$product->id])
      ->inRandomOrder()
      ->select(['id','name','final_price','stock_status','quantity','stock_out_limit','slug','weight_unit','weight_amount','addedby_id','created_at','regular_price','discount','discount_type'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(12);
    
      return view($this->device.'products.productView',compact('product','relatedProducts'));
      
    }
    
    public function productSearch(Request $r){
        
        
        $products = Post::where('type',2)
                    ->where('status','active')
                    // ->where('stock_status',true)
                    // ->where('quantity','>',0)
                    ->where(function($qq) use($r) {
                
                        if($r->search){
                          $qq->where('name','like','%'.$r->search.'%');  
                        }
                        
                      })
                      ->select(['id','name','final_price','stock_status','quantity','stock_out_limit','slug','weight_unit','weight_amount','addedby_id','created_at'])
                      ->whereDate('created_at','<=',date('Y-m-d'))
                      ->paginate(400);
        
        
        if($r->ajax()){
            
            $searchProducts =view($this->device.'products.includes.searchProduct',compact('products'))->render();
            
             return Response()->json([
	            'success' => true,
	            'searchProducts' => $searchProducts,
	            'products' => $products,
	          ]);
        }
        
        
        return view($this->device.'products.productSearch',compact('products','r'));
    }

    public function blogCategory($slug){
      $category =Attribute::latest()->where('type',6)->where('slug',$slug)->first();
      if(!$category){
        return abort('404');
      }

      $posts = Post::whereHas('ctgPosts',function($q) use($category){
        $q->where('reff_id',$category->id);
      })
      ->where(function($qq){
        $qq->where('status','active');
      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);

      return view($this->device.'blogs.categoryPosts',compact('category','posts'));
    }

    public function blogTag($slug){
      $tag =Attribute::latest()->where('type',7)->where('slug',$slug)->first();
      if(!$tag){
        return abort('404');
      }

      $posts = Post::whereHas('tagPosts',function($q) use($tag){
        $q->where('reff_id',$tag->id);
      })
      ->where(function($qq){
        $qq->where('status','active');
      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);

      return view($this->device.'blogs.tagPosts',compact('tag','posts'));
    }

    public function blogAuthor($id,$slug){
      $author =User::find($id);
      if(!$author){
        return abort('404');
      }
      $posts =$author->posts()->where('type',1)->where('status','active')
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);
      return view($this->device.'blogs.authorPosts',compact('author','posts'));

    }

    public function blogView($slug){
      $post =Post::latest()->where('type',1)->where('slug',$slug)->first();
      if(!$post){
        return abort('404');
      }
      return view($this->device.'blogs.blogView',compact('post'));

    }

    public function blogSearch(Request $r){
      $check = $r->validate([
          'search' => 'required|max:100',
      ]);
      
      $posts =Post::latest()->where('type',1)->where('status','active')
      ->where(function($q) use ($r) {

        if($r->search){
          $q->where('search_key','LIKE','%'.$r->search.'%');
        }

      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10)->appends([
        'search'=>$r->search,
      ]);

      return view($this->device.'blogs.blogSearch',compact('posts','r'));

    }

    public function blogComments($id){
      $post =Post::latest()->where('type',1)->where('id',$id)->first();
      if(!$post){
        return abort('404');
      }

      $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100',
          'message' => 'nullable|max:500',
      ]);

      $comments =new Review();

      if(Auth::check()){
      $comments->addedby_id=Auth::id();
      }
      $comments->src_id=$post->id;
      $comments->type=1;
      $comments->name=$r->name;
      $comments->email=$r->email;
      $comments->content=$r->message;
      $comments->save();

      Session()->flash('success','Your Comments successfully Submitted.');

      return back();


    }


    public function pageView($slug){
    
      $page =Post::latest()->where('type',0)->where('slug',$slug)->first();

      if(!$page){
        return abort('404');
      }
      //If deffrent Design or Condition Page Return by ID.

      //Font Home Page
      if($page->id==9){
        return redirect()->route('index');
      }
      
      //Font about Page
      if($page->id==12){
        return view($this->device.'pages.aboutUs',compact('page'));
      }
      

      //Font Contact Page
      if($page->id==13){
        return view($this->device.'pages.contactUs',compact('page'));
      }

      //Latest Blog Page
      if($page->id==10){
        $posts = Post::latest()->where('type',1)->where('status','active')->paginate(10);
        return view($this->device.'blogs.latestBlogs',compact('posts','page'));
      }

      //Latest Services Page
      if($page->id==11){
        $products = Post::latest()->where('type',2)
                    ->where('status','active')
                    ->where('stock_status',true)->where('quantity','>',0)
                    ->paginate(12);
        return view($this->device.'products.latestProducts',compact('products'));
      }

      return view($this->device.'pages.pageView',compact('page'));

    }

   public function contactMail(Request $r){
    //   return $r;
      $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100',
          'subject' => 'required|max:100',
          'message' => 'nullable|max:500',
          'g-recaptcha-response' => 'required',
      ]);
        
        
        // Send a POST request to Google for reCAPTCHA verification
        $recaptchaSecret = '6LcAkugrAAAAAKsOVfmKpEqjvf-oUdWT4tTPFLtl';
        $recaptchaResponse = $r->input('g-recaptcha-response');
        $remoteIp = $r->ip();
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $remoteIp,
        ]);
        $result = $response->json();
        if (!$result['success'] || $result['score'] < 0.5) {
            Session()->flash('error','reCAPTCHA verification failed. Please try again.');
            return back()->withInput();
        }

        if(general()->mail_status && general()->mail_from_address){
            //Mail Data
            $datas =array('r'=>$r);
            $template ='mails.ContactMail';
            $toEmail =general()->mail_from_address;
            $toName =general()->mail_from_name;
            $subject ='Contact Mail Form '.general()->title;
            sendMail($toEmail,$toName,$subject,$datas,$template);
        }

      Session()->flash('success','Your form send successfully done. We are response as soon as possible.');
      return back();
    }

    public function search(Request $r){
      
      if($r->search){

        $posts =Post::where('status','active')
        ->where(function($q){
          $q->where('search_key','LIKE','%'.$r->search.'%');
        })
        ->paginate(24);

      }else{
        $posts = array();
      }

      return view($this->device.'search');

    }

    public function subscribe($email){

      if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $subscribe =PostExtra::latest()->where('type',1)->where('name',$email)->first();

        if(!$subscribe){
          
            $subscribe =new PostExtra();
            $subscribe->type=1;
            $subscribe->name=$email;
            $subscribe->save();

          $message ='<p style="color: #009688;"><span>Success:</span> You Are Successfully Subsribe.</p>';
        }else{
          $message ='<p style="color: #ffc107;"><span>Note:</span> You Are Already Subsribe.Thank You.</p>';
        }

      }else{
        $message ='<p style="color: #ff5722;"><span>Error:</span> Email Are Not validated</p>';
      }

      return Response()->json([
              'success' => true,
              'message' => $message,
            ]);

    }





}
