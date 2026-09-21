<?php

namespace App\Models;

use Cookie;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    //Models Information Data
    /********
     * 
     * type ==0 : Page
     * type ==1 : Post
     * type ==2 : Product
     * 
     * ------------------------
     *  Status==temp, active, inactive
     * ------------------------
     * 
     * Column:
     * 
     * id            =bigint(20):None,
     * name          =varchar(200):null,
     * slug          =varchar(250):null,
     * short_description =text:null,
     * description   =longtext:null,
     * view          =bigint(20):0
     * type          =int(1):0
     * seo_title     =varchar(191):null
     * seo_desc      =text:null
     * seo_keyword   =text:null
     * search_key    =text:null
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


    //Image and Banner Functions Start
    /********
     * 
     * *********/

    public function imageFile(){
    	return $this->hasOne(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',1);
    }

    public function image(){
        if($this->imageFile){
            return $this->imageFile->file_url;
        }else{
            return 'medies/noimage.jpg';
        }
    }

    public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',2);
    }

    public function banner(){
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'medies/no-banner.png';
        }
    }

    public function galleryFiles(){
        return $this->hasMany(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',3);
    }
    
    public function discountPercent(){
        $discount= 0;
        if($this->regular_price > $this->final_price){
                if($this->discount_type=='flat'){
                    $discount = round($this->final_price / $this->regular_price * 100);
                }else{
                    $discount=round($this->discount);
                }
        }
        return $discount;
    }
    
    public function productGalleries(){
        $gallery =null;
        
        $gallery[]=array(
                'image'=>$this->image(),
            );
        
        foreach($this->galleryFiles as $galleryId){
            $gallery[]=array(
                'image'=>$galleryId->image(),
            ); 
        }
        
        
        
        return $gallery;
    }
    //Image and Banner Functions End


    //Post Category tag, comments Functions 
    public function postCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',1)->orderBy('drag','asc');
    }
    
    public function postDatas(){
        return $this->hasMany(PostExtra::class,'src_id')->where('type',4);
    }

    public function postTags(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',2)->orderBy('drag','asc');
    }

    public function postComments(){
        return $this->hasMany(Review::class,'src_id')->where('type',1);
    }
    
    public function postCategories(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',1)->orderBy('drag', 'asc');
    }
    
     public function Tags(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',2)->orderBy('drag', 'asc');
    }

    public function ctgPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',1);
    }

    public function tagPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',2);
    }

    //Post Category tag, comments Functions End


    //Product Functions 
    public function productCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',0)->orderBy('drag','asc');
    }

    public function productTags(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',4)->orderBy('drag','asc');
    }
    
    public function productWeight(){
        $unit =$this->weight_unit?:'';
        
        $UnitWeight ='';
        $weight =0;
        
        if(is_numeric($this->weight_amount)){
            $weight =$this->weight_amount;
        }
        
        if($weight >= 1000){

            if($unit=='gram'){
                $unit='Kg';
            }elseif($unit=='ml'){
                $unit='Liter';
            }
            
            $UnitWeight=$weight/1000;
            
        }else{
            $UnitWeight =$weight;
        }
        
        return $UnitWeight.' '.$unit;
        
    }
    
    public function productWeightUnit(){

        $UnitWeight ='';
        $weight =0;
        
        if(is_numeric($this->weight_amount)){
            $weight =$this->weight_amount;
        }
        
        if($weight >= 1000){

            if($unit=='gram'){
                $unit='Kg';
            }elseif($unit=='ml'){
                $unit='Liter';
            }
            
            $UnitWeight=$weight/1000;
            
        }else{
            $UnitWeight =$weight;
        }
        
        return $UnitWeight;
        
    }

    public function productCategories(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',0)->orderBy('drag', 'asc');
    }

    public function ctgProducts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',0);
    }

    public function extraAttribute(){
        return $this->hasMany(PostExtra::class,'src_id')->where('type',2)->orderBy('drag','asc');
    }

    public function productAttibutes(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',3)->where('parent_id','<>',null)->orderBy('drag','asc');
    }

    public function productSkus(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',4)->where('parent_id','<>',null)->orderBy('drag','asc');
    }

    public function variationSkuMap(){
        $map = [];

        foreach($this->productSkus->groupBy('sku_id') as $skuId=>$rows){
            $first = $rows->first();

            $map[$skuId] = [
                'sku_id'   => $skuId,
                'price'    => ($first->value_1!==null && $first->value_1!=='') ? (float)$first->value_1 : (float)$this->offerPrice(),
                'quantity' => $first->duration!==null ? (int)$first->duration : 0,
            ];
        }

        return $map;
    }
    
    public function productReviews(){
        return $this->hasMany(Review::class,'src_id')->where('type',0);
    }
    
    public function reviewTotal(){
       return $this->productReviews->count();
    }
    
    public function productRating(){
        $reviews =$this->productReviews->count();
        $totalRating =$this->productReviews->sum('rating');
        
        $averageRating =0;
        
        if($reviews > 0 && $totalRating > 0){
            (int)$averageRating =number_format($totalRating/$reviews);
        }
        
        if($averageRating > 5){
            $averageRating =5;
        }
        
        return $averageRating;
        
    }

    public function brand(){
        return $this->belongsTo(Attribute::class,'brand_id');
    }
    
    public function productMinQty(){
        $min =1;
        if($this->min_order_quantity){
           $min =$this->min_order_quantity;
        }
        
        return $min;
    }
    
    public function productMaxQty(){
        $max =$this->quantity;
        if($this->max_order_quantity){
           $max =$this->max_order_quantity;
        }
        
        return $max;
    }
    
    public function offerPrice(){
        $price =$this->final_price;
        return $price;
    }
    
    public function purchasesPrice(){
        $price =$this->purchase_price;
        $purchase =$this->purchases()->latest()->first();
        if($purchase){
          $price=$purchase->price;
        }
        return $price;
    }
    
    public function itemType(){
        $hasFrozen =$this->productCtgs->where('reff_id',166)->first();
        $hasDye =$this->productCtgs->where('reff_id',165)->first();
        if($hasFrozen){
            return 1;
        }elseif($hasDye){
            return 2;
        }else{
            return 0;
        }
    }
    
    public function stockStatus(){
        $status = true;
        
        if($this->stock_status){
            
            if($this->quantity > $this->stock_out_limit){
                if($this->quantity==0){
                    $status = false;
                }
            }else{
                $status = false;
            }
            
        }else{
         $status = false;  
        }
        
        
        return $status;
    }
    
    public function purchases(){
        return $this->hasMany(OrderItem::class,'product_id')->whereHas('order',function($q){
            $q->where('order_type','purchase_order')->where('order_status','confirmed');
        });
    }
    
    public function salesAll(){
        return $this->hasMany(OrderItem::class,'product_id')->whereHas('order',function($q){
            $q->whereIn('order_type',['pos_order','customer_order']);
        });
    }
    
    function saleReportDateWise($type,$from=null,$to=null) {
        $query = $this->salesAll()->where('quantity','>',0)->where('final_price','>',0);
    
        // Apply date filter only if both dates are provided
        if (!empty($from) && !empty($to)) {
            // Convert $from and $to to Carbon instances to ensure correct format
            $from = \Carbon\Carbon::parse($from)->format('Y-m-d H:i:s');
            $to = \Carbon\Carbon::parse($to)->format('Y-m-d H:i:s');
    
            $query->whereBetween('created_at', [$from, $to]);
        }
    
        // Determine sum based on type
        if ($type == 'amount') {
            return $query->sum('final_price');
        } elseif ($type == 'qty') {
            return $query->sum('quantity');
        }
    
        return 0; // Default return if type is invalid
    }
    
    
    public function wishlists()
    {
        return $this->hasMany(WishList::class,'product_id')->where('type',0);
    }

    
    public function comparelists()
    {
        return $this->hasMany(WishList::class,'product_id')->where('type',1);
    }
    

    public function isWl()
    {
        
        
        return (bool) $this->wishlists()->where('cookie', Cookie::get('carts'))->where('type',0)->count();
    }
    
    public function isCP()
    {
        return (bool) $this->comparelists()->where('cookie', Cookie::get('carts'))->where('type',1)->count();
    }

    //Product Functions End


    
    public function user(){
    	return $this->belongsTo(User::class,'addedby_id');
    }

    

    

    
}
