<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('src_id')->nullable();
            $table->tinyInteger('src_type')->nullable()->default(0)->comment('0=media, 1=post, 2=category, 3=attribute, 4=Menus, 5=review, 6=Users 7=General 8=post Attribute');
            $table->tinyInteger('use_Of_file')->nullable()->default(0)->comment('0=media, 1=image, 2=banner, gallery, 4=icon');
            $table->string('file_name', 255)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->string('caption', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('file_url', 191)->nullable();
            $table->string('file_size', 100)->nullable();
            $table->integer('file_type')->default(0)->comment('0=unknown, 1=image, 2=pdf, 3=doc 4=Zip, rar, 5 = Vedio, 6=audio');
            $table->integer('drag')->default(0);
            $table->bigInteger('addedby_id')->nullable();
            $table->bigInteger('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
}
