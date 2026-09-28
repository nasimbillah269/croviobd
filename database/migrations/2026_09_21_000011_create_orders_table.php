<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice')->nullable();
            $table->string('invoice_number', 100)->nullable();
            $table->string('invoice_no', 100)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('old_user_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->integer('division')->nullable();
            $table->integer('district')->nullable();
            $table->integer('city')->nullable();
            $table->text('address')->nullable();
            $table->text('full_address')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('city_name', 200)->nullable();
            $table->string('area_name', 200)->nullable();
            $table->string('company_name', 200)->nullable();
            $table->string('shipping_name', 100)->nullable();
            $table->string('shipping_last_name', 100)->nullable();
            $table->string('shipping_company', 100)->nullable();
            $table->string('shipping_mobile', 20)->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('shipping_fulladdress')->nullable();
            $table->boolean('product_type')->default(0);
            $table->string('delivery_date', 100)->nullable();
            $table->string('delivery_time', 100)->nullable();
            $table->string('order_status', 20)->default('pending')->comment('temp, pending, confirmed, on delivery, completed, cancel, return');
            $table->string('return_status', 20)->default('pending')->comment('pending, confirmed, on delivery, completed, cancel');
            $table->float('return_amount', 10, 2)->default(0);
            $table->string('payment_status', 10)->nullable()->default('unpaid')->comment('unpaid, partial, paid');
            $table->string('payment_method', 100)->nullable();
            $table->string('transection', 100)->nullable();
            $table->boolean('emi_status')->default(0);
            $table->string('order_type', 15)->default('customer_order')->comment('customer_order, pos_order, purchase_order, quotation_order');
            $table->float('total_price', 10, 2)->default(0);
            $table->integer('total_items')->default(0);
            $table->integer('total_qty')->nullable()->default(0);
            $table->float('shipping_charge', 10, 2)->default(0);
            $table->float('tax', 10, 2)->default(0);
            $table->string('discount_type', 20)->nullable()->comment('Percantage, Flat');
            $table->float('discount', 10, 2)->default(0);
            $table->float('discount_price', 10, 2)->default(0);
            $table->float('coupon_discount', 10, 2)->default(0);
            $table->float('used_coin', 10, 2)->default(0);
            $table->float('coin_discount', 10, 2)->default(0);
            $table->float('coin_bonus', 10, 2)->default(0);
            $table->float('grand_total', 10, 2)->default(0);
            $table->float('paid_amount', 10, 2)->default(0);
            $table->float('due_amount', 10, 2)->default(0);
            $table->float('extra_amount', 10, 2)->default(0);
            $table->float('total_purchase', 10, 2)->default(0);
            $table->float('profit_loss', 10, 2)->default(0);
            $table->integer('order_delivery_By')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->string('search_key', 450)->nullable();
            $table->text('corier_result')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('pending_at')->nullable();
            $table->integer('pending_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->text('confirmed_msg')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->integer('shipped_by')->nullable();
            $table->text('shipped_msg')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->integer('delivered_by')->nullable();
            $table->text('delivered_msg')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('cancelled_by')->nullable();
            $table->string('cancelled_reason', 100)->nullable();
            $table->text('cancelled_msg')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->integer('returned_by')->nullable();
            $table->text('returned_msg')->nullable();
            $table->timestamps();
            $table->text('admin_note')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
