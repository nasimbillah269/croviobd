<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

    //Models Information Data
    /********
     * type ==0 : Category
     * type ==1 : Slider
     * type ==2 : Brand
     * type ==3 : Client
     * type ==4 : Galleries
     * type ==5 : Portfolio
     * type ==6 : Blog Category
     * type ==7 : Blog Tags
     * type ==8 : Menus
     * type ==9 : Attributes view== 1:text, 2:Color, 3:Image 
     * type ==10 : Products Tags
     * 
     * ------------------------
     *  Status==temp, active, inactive
     * ------------------------
     * 
     * Column:
     * id            =bigint(20):None, 
     * name          =varchar(191):null,
     * slug          =varchar(255):null,
     * parent_id     =bigint(20):null
     * category_id   =bigint(20):null
     * src_id        =bigint(20):null
     * short_description = text():null
     * description   =text():null
     * view          =bigint(20):0
     * menu_type     =tinyint(1):null 0=Custom Link, 1=Pages, 2=Post Categories, 3=Service Categories;
     * location      =varchar(200):null
     * target        =tinyint(1):0
     * icon          =varchar(200):null
     * seo_title     =varchar(200):null
     * seo_description  =text:null
     * seo_keyword   =text:null
     * data_counts   =longtext:null json format For Reports Count
     * type          =int(1):0 0=Category, 1=Slider, 2=Brand, 3=Client, 4=Galleries, 5=Portfolio, 6=Blog Category, 7=Blog Tags 8=Menus
     * status        =varchar(10):temp temp, active, inactive
     * fetured       =tinyint(1):0
     * addedby_id    =bigint(20):null
     * editedby_id   =bigint(20)::null
     * created_at    =timestamp:null
     * updated_at    =timestamp:null
     * 
     * 
     * *********/


    //Image and Banner Functions Start
    /********
     * 
     * *********/
    public function imageFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',3)->where('use_Of_file',1);
    }

    public function image(){
        if($this->imageFile){
            return $this->imageFile->file_url;
        }else{
            return 'medies/noimage.jpg';
        }
    }

    public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',3)->where('use_Of_file',2);
    }

    public function banner(){
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'medies/no-banner.png';
        }
    }

    public function galleryImages(){
        return $this->hasMany(Media::class,'src_id')->where('use_Of_file',3)->orderBy('drag','asc');
    }

    //Image and Banner Functions End


    public function subctgs(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','<>','temp');
    }

    public function parent(){
        return $this->belongsTo(Attribute::class,'parent_id')->where('status','<>','temp');
    }
    
    public function posts(){
        return $this->belongsToMany(Post::class,PostAttribute::class,'reff_id','src_id');
    }

    public function ctgPosts(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',1);
    }

    public function ctgProducts(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',0);
    }

    public function subAttributes(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','<>','temp');
    }

    public function attributeItems(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',3)->orderBy('drag','asc');
    }

    public function tagPosts(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',2);
    }

    //Slider Functions Start
    
    public function subSliders(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','active')->orderBy('view','asc');
    }

    public function sliderItems(){
        return $this->hasMany(Attribute::class,'parent_id')->orderBy('view','asc');
    }

    public function methodOptions(){
        return $this->hasMany(Attribute::class,'parent_id')->where('type',11)->where('status','<>','temp');
    }

    public function methodOptionsActive(){
        return $this->hasMany(Attribute::class,'parent_id')->where('type',11)->where('status','active');
    }

    //Slider Functions End
    

    //Menu Functions Start
    /********
     * Note: menu_type ==1 : Custom Link
     *       menu_type ==2 : blog Category
     *       menu_type ==3 : Service Category
     * 
     * *********/

    public function pagelink(){
       return $this->belongsTo(Post::class,'src_id')->where('type',0)->where('status','<>','temp');
    }

    public function blogCtglink(){
       return $this->belongsTo(Attribute::class,'src_id')->where('type',6)->where('status','<>','temp');
    }
    
    public function productCtglink(){
       return $this->belongsTo(Attribute::class,'src_id')->where('type',0)->where('status','<>','temp');
    }
    
    public function subMenus(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','<>','temp')->orderBy('view','asc');
    }
    
    public function MenuItems(){
        return $this->hasMany(Attribute::class,'category_id')->where('status','<>','temp')->orderBy('view','asc');
    }

    //Menu Slug
    public function munuLink(){

        if($this->menu_type==1){
            if($this->pagelink){
                if($this->pagelink->id==9){
                    return route("index");
                }else{
                    return $this->pagelink->slug;
                }
                
            }

        }elseif($this->menu_type==2){
            if($this->blogCtglink){
                return 'blog/category/'.$this->blogCtglink->slug;
            }
        }elseif($this->menu_type==3){
            if($this->productCtglink){
                return 'product/category/'.$this->productCtglink->slug;
            }
        }else{
            return $this->slug;
        }

    }
    //Menu Name
    public function munuName(){

        if($this->menu_type==1){
            if($this->pagelink){
                return $this->pagelink->name;
            }

        }elseif($this->menu_type==2){
            if($this->blogCtglink){
                return $this->blogCtglink->name;
            }
        }elseif($this->menu_type==3){
            if($this->productCtglink){
                return $this->productCtglink->name;
            }
        }else{
            return $this->name;
        }
        
    }
    
    //Menu Name
    public function munuImg(){

        if($this->productCtglink){
            return $this->productCtglink->image();
        }
        
    }
    
    //********************************
    //Menu Functions End
    
    public function user(){
        return $this->belongsTo(User::class,'addedby_id');
    }

    

    
}
