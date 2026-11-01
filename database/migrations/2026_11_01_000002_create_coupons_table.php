<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('discount_type', 20)->default('percentage')->comment('percentage, flat');
            $table->float('discount', 10, 2)->default(0);
            $table->float('minimum_shopping', 10, 2)->default(0);
            $table->float('max_discount', 10, 2)->default(0)->comment('0 = no limit');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->integer('usage_limit')->default(0)->comment('0 = unlimited');
            $table->integer('used_count')->default(0);
            $table->string('status', 10)->default('active')->comment('active, inactive');
            $table->integer('addedby_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
