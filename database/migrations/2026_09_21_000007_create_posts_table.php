<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('slug', 250)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku_code', 100)->nullable();
            $table->string('bar_code', 100)->nullable();
            $table->integer('stock_out_limit')->default(0);
            $table->boolean('stock_status')->default(1);
            $table->boolean('final_stock_status')->default(1);
            $table->integer('quantity')->nullable();
            $table->float('purchase_price', 10, 2)->default(0);
            $table->float('final_price', 10, 2)->default(0);
            $table->float('pos_price', 10, 2)->default(0);
            $table->float('discount', 10, 2)->default(0);
            $table->string('discount_type', 20)->nullable();
            $table->float('regular_price', 10, 2)->default(0);
            $table->timestamp('offer_start_date')->nullable();
            $table->timestamp('offer_end_date')->nullable();
            $table->integer('min_order_quantity')->default(1);
            $table->integer('max_order_quantity')->nullable();
            $table->string('weight_unit', 100)->nullable();
            $table->string('weight_amount', 50)->nullable();
            $table->string('dimensions_unit', 100)->nullable();
            $table->string('dimensions_length', 50)->nullable();
            $table->string('dimensions_width', 50)->nullable();
            $table->string('dimensions_height', 50)->nullable();
            $table->boolean('variation_status')->default(0);
            $table->boolean('pos_status')->default(0);
            $table->boolean('emi_status')->default(0);
            $table->boolean('digital_status')->default(0);
            $table->boolean('classified_status')->default(0);
            $table->string('product_source', 50)->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('sell_count')->default(0);
            $table->boolean('product_type')->default(0);
            $table->unsignedBigInteger('view')->default(0);
            $table->integer('type')->default(0)->comment('0=Page,1=Post, 2=Product');
            $table->string('seo_title', 191)->nullable();
            $table->text('seo_desc')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->text('search_key')->nullable();
            $table->string('status', 10)->default('temp')->comment('temp,active,inactive');
            $table->boolean('fetured')->default(0);
            $table->integer('old_id')->nullable();
            $table->bigInteger('addedby_id')->nullable();
            $table->integer('old_addedby_id')->nullable();
            $table->bigInteger('editedby_id')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('quantity');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
