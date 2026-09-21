<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttribute extends Model
{

    //Models Information Data
    /********
     * type ==0 : Category Post
     * type ==1 : blog Category Post
     * type ==2 : Blog Tags post
     * type ==3 : Product Attribute post
     * 
     * ------------------------
     *  Status==
     * ------------------------
     * 
     * 
     * Column:
     * 
     * id            =bigint(20):None,
     * src_id        =bigint(20):null
     * parent_id     =bigint(20):null
     * reff_id       =bigint(20):null
     * type          =tinyint(1):null
     * status        =varchar(20):null
     * duration      =int(6):null
     * drag          =bigint(20):null
     * addedby_id    =bigint(20):null
     * created_at    =timestamp:null
     * updated_at    =timestamp:null
     * 
     * 
     * 
     ****/

    public function imageFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',8)->where('use_Of_file',1);
    }

    public function image(){
        if($this->imageFile){
            return $this->imageFile->file_url;
        }else{
            return 'medies/noimage.jpg';
        }
    }

    public function skuImageFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',10)->where('use_Of_file',1);
    }

    public function skuImage(){
        if($this->skuImageFile){
            return $this->skuImageFile->file_url;
        }else{
            return 'medies/noimage.jpg';
        }
    }


    public function post(){
        return $this->belongsTo(Post::class,'src_id');
    }

    public function attributeItem(){
        return $this->belongsTo(Attribute::class,'parent_id');
    }

    public function attribute(){
        return $this->belongsTo(Attribute::class,'reff_id');
    }

    public function attributeCheck(){
        return $this->belongsTo(PostAttribute::class,'reff_id','reff_id')->where('type',4);
    }


    public function skuList(){
        return $this->hasMany(PostAttribute::class,'sku_id','sku_id')->where('type',4);
    }

    public function skuLists(){
        return $this->hasMany(PostAttribute::class,'reff_id','reff_id')->where('type',3)->orderBy('drag','asc');
    }

    public function skuCheck(){
        return $this->belongsTo(PostAttribute::class,'parent_id','parent_id')->where('type',4);
    }



}
