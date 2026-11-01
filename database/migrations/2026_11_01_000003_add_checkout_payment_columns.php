<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckoutPaymentColumns extends Migration
{
    public function up()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->string('bangla_qr_image', 200)->nullable();
            $table->integer('cod_advance_percent')->default(50);
            $table->boolean('full_advance_free_delivery')->default(0);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('delivery_zone_id')->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->float('advance_amount', 10, 2)->default(0);
            $table->string('sender_number', 20)->nullable();
            $table->string('payment_screenshot', 200)->nullable();
        });
    }

    public function down()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->dropColumn(['bangla_qr_image', 'cod_advance_percent', 'full_advance_free_delivery']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_zone_id', 'coupon_code', 'advance_amount', 'sender_number', 'payment_screenshot']);
        });
    }
}
