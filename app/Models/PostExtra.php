<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostExtra extends Model
{
    
    //Models Information Data
    /********
     * 
     * type ==0 : Page
     * 
     * ------------------------
     *  Status==
     * ------------------------
     * 
     * Column:
     * 
     * id            =bigint(20):None,
     * src_id        =bigint(20):null,
     * name          =varchar(191):null,
     * content       =text:null,
     * parent_id     =bigint(20):null,
     * drag          =int(5):0
     * type          =int(1):0
     * status        =varchar(10):null
     * fetured       =tinyint(1):null
     * addedby_id    =bigint(20):null
     * editedby_id   =bigint(20)::null
     * created_at    =timestamp:null
     * updated_at    =timestamp:null
     * 
     * 
     * 
     ****/
     
      public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',9)->where('use_Of_file',2);
    }

    public function banner(){
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'medies/no-banner.png';
        }
    }
     
     
    public function zoneLists(){
         return $this->hasMany(PostExtra::class,'parent_id')->where('type',3);
     }
     
     public function zoneId(){
         return $this->belongsTo(Country::class,'src_id');
     }
     
     public function parentId(){
         return $this->belongsTo(PostExtra::class,'parent_id');
     }
     
     public function product(){
         return $this->belongsTo(Post::class,'src_id');
     }
     
       public function productsList(){
         
         $products = Post::where('type',2)->where('status','active')->where('stock_status',true)
        //  ->where('quantity','>',0)
         ->whereHas('postDatas',function($q) { 
                            $q->where('parent_id',$this->id);
                    })->limit($this->product_limit)->get();
         
         if($this->product_type==4 || $this->product_type==5 || $this->product_type==6 || $this->product_type==7 && $this->product_limit){
             

             $products = Post::where('type', 2)
                ->where('status', 'active')
                ->where('stock_status', true);
                // ->where('quantity', '>', 0);
                
                if($this->product_type == 6){
                  $products =$products->orderBy('quantity','desc')->where('discount','>',0);
                }elseif($this->product_type == 4){
                    $products =$products->orderBy('quantity','desc');
                }elseif($this->product_type == 7){
                    $products =$products->orderBy('sell_count', 'desc');
                    // $products =$products->whereHas('salesAll',function($q){
                    //     $q->where('quantity','>',0);
                    // })
                    // // ->withSum('salesAll','quantity');
                    // ->withSum('salesAll as total_sales_quantity', 'quantity') // Calculate total sales quantity
                    // ->orderByDesc('total_sales_quantity');
                }

                // $products =$products->limit($this->product_limit)->get();
         
         }
         
         
         return $products;
     }
     
     
     public function products(){
         
         $products = Post::where('type',2)->where('status','active')->where('stock_status',true)->where('quantity','>',0)->whereHas('postDatas',function($q) { 
                            $q->where('parent_id',$this->id);
                    })->limit($this->product_limit)->get();
         
         if($this->product_type==4 || $this->product_type==5 || $this->product_type==6 || $this->product_type==7 && $this->product_limit){
             

             $products = Post::where('type', 2)
                ->where('status', 'active')
                ->where('stock_status', true)
                ->where('quantity', '>', 0);
                
                if($this->product_type == 6){
                  $products =$products->where('fetured', true);
                }elseif($this->product_type == 4 || $this->product_type == 6){
                    $products =$products->latest();
                }elseif($this->product_type == 7){
                    $products =$products->orderBy('sell_count', 'desc');
                    // $products =$products->whereHas('salesAll',function($q){
                    //     $q->where('quantity','>',0);
                    // })
                    // // ->withSum('salesAll','quantity');
                    // ->withSum('salesAll as total_sales_quantity', 'quantity') // Calculate total sales quantity
                    // ->orderByDesc('total_sales_quantity');
                }

                $products =$products->limit($this->product_limit)->get();
         
         }
         
         
         return $products;
     }
     
     public function homeDataIds(){
         return $this->hasMany(PostExtra::class,'parent_id')->where('type',4);
     }
     
     

}
