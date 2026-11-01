<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryZonesTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('bn_name', 100)->nullable();
            $table->integer('division_id')->nullable();
            $table->string('scope', 10)->default('all')->comment('all, city, outer');
            $table->float('shipping_charge', 10, 2)->default(0);
            $table->integer('drag')->default(0);
            $table->string('status', 10)->default('active')->comment('active, inactive');
            $table->timestamps();
        });

        // Dhaka metro thanas are flagged so "Dhaka City" and "Outer Dhaka" zones can split Dhaka District
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('is_city')->default(0)->after('parent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_zones');
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('is_city');
        });
    }
}
