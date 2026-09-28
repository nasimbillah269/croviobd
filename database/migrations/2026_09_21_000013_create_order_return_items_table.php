<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_return_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('order_item_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('seller_id')->nullable();
            $table->integer('sold_quantity')->nullable();
            $table->integer('return_quantity')->nullable();
            $table->float('sold_price', 10, 2)->default(0);
            $table->float('return_price', 10, 2)->default(0);
            $table->float('finaly_return', 10, 2)->default(0);
            $table->float('vat', 10, 2)->default(0);
            $table->string('return_status', 20)->nullable();
            $table->string('reasion', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending');
            $table->boolean('return_type')->default(0)->comment('0=cancel, 1=return');
            $table->boolean('accepted')->default(0);
            $table->text('search_key')->nullable();
            $table->timestamp('pending_at')->nullable();
            $table->integer('pending_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->integer('delivered_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('cancelled_by')->nullable();
            $table->integer('addedby_id')->nullable();
            $table->integer('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_return_items');
    }
}
