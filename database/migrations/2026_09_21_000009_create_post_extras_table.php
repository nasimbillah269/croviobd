<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostExtrasTable extends Migration
{
    public function up()
    {
        Schema::create('post_extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('src_id')->nullable();
            $table->string('name', 191)->nullable();
            $table->text('content')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->integer('drag')->default(0);
            $table->float('shipping_charge', 10, 2)->default(0);
            $table->boolean('product_type')->default(0);
            $table->boolean('product_view')->default(0);
            $table->boolean('banner_status')->default(0);
            $table->string('banner_link', 100)->nullable();
            $table->integer('product_limit')->nullable();
            $table->string('bg_color', 100)->nullable();
            $table->string('title_color', 100)->nullable();
            $table->string('status', 10)->nullable();
            $table->integer('type')->default(0)->comment('0=Page,1=Subscribe, 2=Product Extra Attribute, 3= Shipping Zone, 4=Home Product Data');
            $table->bigInteger('addedby_id')->nullable();
            $table->bigInteger('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_extras');
    }
}
