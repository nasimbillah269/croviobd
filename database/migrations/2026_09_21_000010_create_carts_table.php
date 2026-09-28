<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->date('trans_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('color', 255)->nullable();
            $table->string('size', 255)->nullable();
            $table->bigInteger('sku_id')->nullable();
            $table->boolean('product_type')->default(0);
            $table->boolean('cart_type')->default(0)->comment('0=add to card, 1=order now');
            $table->text('cookie')->nullable();
            $table->integer('quantity')->default(0);
            $table->boolean('emi')->default(0);
            $table->integer('coupon_id')->nullable();
            $table->integer('address')->nullable();
            $table->unsignedBigInteger('addedby_id')->nullable();
            $table->unsignedBigInteger('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
