<?php

namespace App\Http\Controllers\Api;


use Image;
use PDF;
use Auth;
use Hash;
use Session;
use Mail;
use Cookie;
use Validator;
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


class ApiWelcomeController extends Controller
{

    public function __construct()
    {

    }
    
    public function slider(Request $request){

        //return  $request; $request->getContent(); 
        
        $sliders =slider('Front Page Slider');
        
        if($sliders){
            if($sliders->subSliders->count()>0){
                $sliders = $sliders->subSliders()->select(['id','name','description'])->get();
                collect($sliders)->map(function($item){
                    $item->image = $item->image();
                    unset($item->imageFile);
                    return $item;
                });
            }
        }
        
        return Response()->json($sliders);
    }
    
    
    public function homeCategories(){
        
        $categories =menu('Home Category');
        $ctgs=null;
        if($categories){
            if($categories->subMenus->count()>0){
                $categories=$categories->subMenus()->get();
                
                foreach($categories as $ctg){
                    if($ctgR=$ctg->productCtglink){
                        $ctgs[]=array('id'=>$ctgR->id,'name'=>$ctgR->name,'slug'=>$ctgR->slug,'image'=>$ctgR->image());  
                    }
                }
                
            }
        }
        
        return Response()->json($ctgs);
    }
    
    public function pagesMenus(){
        
        $pagesItems =menu('Header Menus');
        $pages=null;
        if($pagesItems){
            if($pagesItems->subMenus->count()>0){
                $pagesIds=$pagesItems->subMenus()->get();
                
                foreach($pagesIds as $pageid){
                    if($pageid->menu_type==1){
                        if($page=$pageid->pagelink){
                            $datas=null;
                            $pages[]=array('id'=>$page->id,'name'=>$page->name,'slug'=>$page->slug);
                        }
                    }
                }
                
            }
        }
        
        return Response()->json($pages);
    }
    
    public function pageView($id){
        $page=Post::where('type',0)->find($id);
        if(!$page){
            return response()->json(['message'=>'Page Are Not Found'],404); 
        }
        
        $datas=null;
        
        if($page->id==40){
            $categories = Attribute::where('parent_id',112)->where('status','active')->orderBy('name','asc')->get();
            foreach($categories as $ctg){
                $datas[]=array('id'=>$ctg->id,'name'=>$ctg->name,'slug'=>$ctg->slug,'image'=>$ctg->image());
            }
            
        }
        
        $pageContent =array(
                'id'=>$page->id,
                'name'=>$page->name,
                'slug'=>$page->slug,
                'image'=>$page->image(),
                'description'=>$page->description,
                'datas'=>$datas,
            );
        
        return Response()->json($pageContent);
    }
    
    public function homeProducts(Request $request){
        $homeProducts=null;
        $type=4;
        if($request->type=='horizontal'){
            $type=7;
        }
        
        $homeDatas =PostExtra::where('type',4)->where('parent_id',null)->where('status','active')->where('product_type',$type)->orderBy('drag','asc')->first();
        if($homeDatas){
            $products=array();
            foreach($homeDatas->products() as $product){
                $products[] =array('id'=>$product->id,'name'=>$product->name,'regular_price'=>$product->regular_price,'final_price'=>$product->final_price,'stock_status'=>$product->stock_status,'quantity'=>$product->quantity,'stock_out_limit'=>$product->stock_out_limit,'weight_unit'=>$product->weight_unit,'weight_amount'=>$product->weight_amount,'slug'=>$product->slug,'weight'=>$product->productWeight(),'url'=>url('/product/'.$product->slug),'image'=>$product->image());
            }
            $homeProducts = array('name'=>$homeDatas->name,'products'=>$products);
        }
        return $homeProducts;
    }
    
    public function homeProductsGrid(){
        
        $homeProducts=null;
        $homeDatas =PostExtra::where('type',4)->where('parent_id',null)->where('status','active')->orderBy('drag','asc')->get();
        
        if($homeDatas->count()>0){
            
            foreach($homeDatas as $data){
                $products = $data->products();
                
                if($products->count()>0){
                    
                    $items=null;
                    foreach($products as $product){
                        $image =$product->image();
                        $items[] =array('id'=>$product->id,'name'=>$product->name,'regular_price'=>(string) priceFormat($product->regular_price),'final_price'=>(string) priceFormat($product->final_price),'stock_status'=>$product->stock_status,'quantity'=>$product->quantity,'stock_out_limit'=>$product->stock_out_limit,'slug'=>$product->slug,'weight'=>$product->productWeight(),'image'=>$image);
                    }
                    $products =$items;
                }else{
                    $products=null;
                }
                
                $homeProducts[] = array('name'=>$data->name,'products'=>$products);
            }
        
        }
        
        return Response()->json($homeProducts);
    }
    
    public function mayYouLikeProducts(Request $request){
        
        $perPage =$request->page_size?:10;
        
        $products =Post::latest()
                ->where('type',2)
                ->where('status','active')
                ->where('stock_status',true)
                ->where('quantity','>',0)
                ->select(['id','name','regular_price','final_price','stock_status','quantity','stock_out_limit','weight_unit','weight_amount','slug'])
                ->whereDate('created_at','<=',date('Y-m-d'))
                ->paginate($perPage);
                
        collect($products->items())->map(function($item){
            $item->regular_price=(string) number_format($item->regular_price);
            $item->final_price=(string) number_format($item->final_price);
            $item->image=$item->image();
            $item->weight=$item->productWeight();
            unset($item->imageFile);
            return $item;
        });
        
        return Response()->json($products);
        return Response()->json(['data'=>$products->items(),'links'=>$links,'meta'=>$meta]);
        
    }
    
    
    public function generalInfo(){
        $general =general()->select('id','title','subtitle','website','logo','favicon','mobile','email','address_one','facebook_link','twitter_link','instagram_link','linkedin_link','pinterest_link','youtube_link')->first();
        
        return Response()->json($general);
    }
    
    
    
    
    public function categories(Request $request){
        
        $categoriesAll =menu('Category Menus');
        $categories=array();
        if($categoriesAll){
          
            if($categoriesAll->subMenus->count()>0){
             
                foreach($categoriesAll->subMenus()->get() as $ctg){
          
                    if($ctgR=$ctg->productCtglink){
                        
                        $subCategory =array();
                       
                        if($ctgR->subctgs->count()>0){
                            foreach($ctgR->subctgs as $subCtg){
                                    $subCategory2=array();
                                    
                                    if($subCtg->subctgs->count()>0){
                                        foreach($subCtg->subctgs as $subCtg2){
                                            $subCategory3=array();
                                            $subCategory[]=array('id'=>$subCtg2->id,'name'=>$subCtg2->name,'slug'=>$subCtg2->slug,'parent_id'=>$subCtg2->parent_id,'image'=>$subCtg2->image(),'subCategory'=>$subCategory3);
                                        }
                                    }
                                    
                                    $subCategory[]=array('id'=>$subCtg->id,'name'=>$subCtg->name,'slug'=>$subCtg->slug,'parent_id'=>$subCtg->parent_id,'image'=>$subCtg->image(),'subCategory'=>$subCategory2);
                            }
                        }
                        
                        
                        $categories[]=array('id'=>$ctgR->id,'name'=>$ctgR->name,'slug'=>$ctgR->slug,'parent_id'=>$ctgR->parent_id,'image'=>$ctgR->image(),'subCategory'=>$subCategory);
                    }
                }
                
            }
        }
        
        return $categories;
        
        $categories = Attribute::latest()->where('type',0)->where('status','active')->select(['id','name','slug','parent_id'])->get();
        
        collect($categories)->map(function($item){
            
            $subCtgg =$item->subctgs()->select(['id','name','slug','parent_id'])->get();
            
            collect($subCtgg)->map(function($item){
                
                    $subCtgg2 =$item->subctgs()->select(['id','name','slug','parent_id'])->get();
                    collect($subCtgg2)->map(function($item){
                        $item->image =$item->image();
                        unset($item->imageFile);
                        return $item;
                    });
                    
                $item->image =$item->image();
                $item->subCategory= $subCtgg2;
                unset($item->imageFile);
                unset($item->subctgs);
                return $item;
            });
            
            $item->image =$item->image();
            $item->subCategory =$subCtgg;
            
            unset($item->imageFile);
            unset($item->subctgs);
            return $item;
        });

        
        return Response()->json($categories);
    }
    
    public function categoryView(Request $request,$slug){
        
        $category =Attribute::latest()->where('type',0)
        //->where('slug',$slug)
        ->where('id',$slug)
        ->select(['id','name','slug'])->first();
        if(!$category){
            return response()->json(['message'=>'Category Are Not Found'],404); 
        }
        $perPage =$request->page_size?:10;
        $products = Post::whereHas('ctgProducts',function($q) use($category){
                    $q->where('reff_id',$category->id);
                  })
                  ->where(function($qq){
                    $qq->where('status','active')
                    ->where('stock_status',true)
                    //->where('stock_out_limit','>',1)
                    ->where('quantity','>',0);
                  });
          
          
           //Price Min To Max
            if($request->minPrice && $request->maxPrice){
                $min =is_numeric($request->minPrice)?$request->minPrice:0;
                $max =is_numeric($request->maxPrice)?$request->maxPrice:0;
                $products=$products->where('final_price','>=',$min)->where('final_price','<=',$max);
            }elseif($request->minPrice){
                $min =is_numeric($request->minPrice)?$request->minPrice:0;
                $products=$products->where('final_price','>=',$min);
            }elseif($request->maxPrice){
                $max =is_numeric($request->maxPrice)?$request->maxPrice:0;
                $products=$products->where('final_price','<=',$max);
            }

          
          //Price latest to Oldtest
            if($request->order_by=='new'){
                $products=$products->orderBy('id','desc');
            }else{
                $products=$products->orderBy('id','asc');
            }
            
            
            //Price Low To Hight
            // if($request->sort=='low_high'){
            //     $products=$products->orderBy('final_price','asc');
            // }
            
            //Price Hight To Low
            // if($request->sort=='high_low'){
            //     $products=$products->orderBy('final_price','desc');
            // }
            
            $products = $products->select(['id','name','regular_price','final_price','stock_status','quantity','stock_out_limit','weight_unit','weight_amount','slug'])
            ->whereDate('created_at','<=',date('Y-m-d'))
            ->paginate($perPage)->appends([
                    'page_size'=>$request->page_size,
                    'minPrice'=>$request->minPrice,
                    'maxPrice'=>$request->maxPrice,
                    'order_by'=>$request->order_by,
                    'sort'=>$request->sort,
                  ]);
          
          collect($products->items())->map(function($item){
              $item->image =$item->image();
              $item->weight=$item->productWeight();
              unset($item->imageFile);
              return $item;
          });
        
        return Response()->json(['category'=>$category,'products'=>$products]);
        
    }
    
    public function products(){
        $products = Post::latest()
          ->where(function($qq){
            $qq->where('status','active')
            ->where('stock_status',true)
            //->where('stock_out_limit','>',1)
            ->where('quantity','>',0);
          })
          ->select(['id','name','final_price','stock_status','quantity','stock_out_limit','slug'])
          ->whereDate('created_at','<=',date('Y-m-d'))
          ->paginate(12);
          
          collect($products->items())->map(function($item){
              $item->image =$item->image();
              $item->weight=$item->productWeight();
              unset($item->imageFile);
              return $item;
          });
        return Response()->json($products);
    }
    
    public function productView($slug){
        $product = Post::latest()
          ->where('type',2)
          ->where('status','active')
          ->whereDate('created_at','<=',date('Y-m-d'))
          //->where('slug',$slug)
          ->where('id',$slug)
          ->select(['id','name','slug','short_description','description','stock_out_limit','stock_status','quantity','final_price','discount','discount_type','regular_price','min_order_quantity','max_order_quantity','weight_unit','weight_amount','dimensions_unit','dimensions_length','dimensions_width','dimensions_height','variation_status','brand_id','seo_title','seo_desc','seo_keyword'])
          ->first();
        if(!$product){
            return response()->json(['message'=>'Product Are Not Found'],404); 
        }
        
        $product->url =url('/product/'.$product->slug);
        $product->total_rating =(int)$product->reviewTotal();
        $product->avg_rating =(string) $product->productRating();
        $product->image =$product->image();
        $product->weight=$product->productWeight();
        $product->gallery=$product->productGalleries();
        
        unset($product->imageFile);
        unset($product->galleryFiles);
        
        $category = $product->ctgProducts->pluck('reff_id');
        $ctgs =null;
        foreach($product->productCategories as $ctg){
                $ctgs[]=array('id'=>$ctg->id,'slug'=>$ctg->slug,'name'=>$ctg->name);
        }
        $product->variants =[];
        $product->categories =$ctgs;
        
        
        $review = $product->productReviews()->select(['id','addedby_id','content','rating','created_at'])->limit(3)->get();
        
        
        collect($review)->map(function($item){
            $item->name=$item->user?$item->user->name:'No User';
            $item->image=$item->user->image();
            unset($item->user);
            return $item;
            
        });
        
        $product->reviews =$review;
        
        $relatedProducts = Post::where('type',2)->whereHas('ctgProducts',function($q) use($category){
            $q->whereIn('reff_id',$category);
          })
          ->where(function($qq){
            
            $qq->where('status','active')
            ->where('stock_status',true)
            //->where('stock_out_limit','>',1)
            ->where('quantity','>',0);
            
          })
          ->whereNotIn('id',[$product->id])
          ->inRandomOrder()
          ->select(['id','name','regular_price','final_price','stock_status','quantity','stock_out_limit','weight_unit','weight_amount','slug'])
          ->whereDate('created_at','<=',date('Y-m-d'))
          ->limit(12)->get();
          
        collect($relatedProducts)->map(function($item){
              $item->url =url('/product/'.$item->slug);
              $item->image =$item->image();
              $item->weight=$item->productWeight();
              unset($item->imageFile);
              return $item;
          });
        
        unset($product->ctgProducts);
        unset($product->productCategories);
        
        return Response()->json(['product'=>$product,'relatedProducts'=>$relatedProducts]);
        
    }
    
    public function productSearch(Request $request){
        $rules = [
          'search' => 'nullable|max:200',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
         return response()->json($validator->messages(), 500);
        }
        
        $perPage =$request->page_size?:10;

        
        $products = Post::where('type',2)->where('status','active')->where('stock_status',true)->where('quantity','>',0)->where(function($qq) use($request) {
                
                    if($request->search){
                      $qq->where('name','like','%'.$request->search.'%');  
                    }
                    
                  });
              
            if($request->category_id){
                $category =Attribute::latest()->where('type',0)->find($request->category_id);
                if($category){
                    $products =$products->whereHas('ctgProducts',function($q) use($category){
                        $q->where('reff_id',$category->id);
                      });
                }
            }
            
            //Price Min To Max
            if($request->minPrice && $request->maxPrice){
                $min =is_numeric($request->minPrice)?$request->minPrice:0;
                $max =is_numeric($request->maxPrice)?$request->maxPrice:0;
                $products=$products->where('final_price','>=',$min)->where('final_price','<=',$max);
            }elseif($request->minPrice){
                $min =is_numeric($request->minPrice)?$request->minPrice:0;
                $products=$products->where('final_price','>=',$min);
            }elseif($request->maxPrice){
                $max =is_numeric($request->maxPrice)?$request->maxPrice:0;
                $products=$products->where('final_price','<=',$max);
            }

          
          //Price latest to Oldtest
            if($request->order_by=='old'){
                $products=$products->orderBy('id','desc');
            }else{
                $products=$products->orderBy('id','asc');
            }
              
            //Price Min To Max
            // if($request->minPrice && $request->maxPrice){
            //     $min =is_numeric($request->minPrice)?$request->minPrice:0;
            //     $max =is_numeric($request->maxPrice)?$request->maxPrice:0;
            //     $products=$products->where('final_price','>=',$min)->where('final_price','<=',$max);
            // }
            
            // if($request->minPrice){
            //     $min =is_numeric($request->minPrice)?$request->minPrice:0;
            //     $products=$products->where('final_price','>=',$min);
            // }
            
            // if($request->maxPrice){
            //     $max =is_numeric($request->maxPrice)?$request->maxPrice:0;
            //     $products=$products->where('final_price','<=',$max);
            // }
            
            
          
          //Price latest to Oldtest
            // if($request->order_by=='old'){
            //     $products=$products->orderBy('created_at','asc');
            // }else{
            //     $products=$products->orderBy('created_at','desc');
            // }
            
            
            //Price Low To Hight
            if($request->sort=='low_high'){
                $products=$products->orderBy('final_price','asc');
            }
            
            //Price Hight To Low
            if($request->sort=='high_low'){
                $products=$products->orderBy('final_price','desc');
            }
            
            $products = $products->select(['id','name','regular_price','final_price','stock_status','quantity','stock_out_limit','weight_unit','weight_amount','slug'])
            ->whereDate('created_at','<=',date('Y-m-d'))
            ->paginate($perPage)->appends([
                    'page_size'=>$request->page_size,
                    'minPrice'=>$request->minPrice,
                    'maxPrice'=>$request->maxPrice,
                    'order_by'=>$request->order_by,
                    'sort'=>$request->sort,
                  ]);

        
        collect($products->items())->map(function($item){
              $item->url =url('/product/'.$item->slug);
              $item->image =$item->image();
              $item->weight=$item->productWeight();
              unset($item->imageFile);
              return $item;
          });
        
        
        return Response()->json($products);
    }
    
    public function subscribe(Request $request){
        $rules = [
          'email' => 'required|max:100',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
         return response()->json($validator->messages(), 500);
        }
        
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            
            $subscribe =PostExtra::latest()->where('type',1)->where('name',$request->email)->first();
    
            if(!$subscribe){
                $subscribe =new PostExtra();
                $subscribe->type=1;
                $subscribe->name=$request->email;
                $subscribe->save();
    
              return response()->json(['message'=>'You Are Successfully Subsribe. Thank You.']);
            }else{
              return response()->json(['message'=>'You Are Already Subsribe.Thank You.'],500); 
            }
    
          }else{
            return response()->json(['message'=>'Email Are Not validated'],500);
          }

    }
    
    
    // public function pageView($slug){

    //     $page =Post::latest()->where('type',0)->where('slug',$slug)->select(['id','name','slug','short_description','description'])->first();
    
    //     if(!$page){
    //       return response()->json(['message'=>'Page Are Not Found'],404); 
    //     }

        
    //     return Response()->json($page);
    // }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}