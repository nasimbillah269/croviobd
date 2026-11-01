<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoneDistrictChargesAndAdvanceVerification extends Migration
{
    public function up()
    {
        // Optional per-district override of a zone's delivery charge
        Schema::create('delivery_zone_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('delivery_zone_id');
            $table->integer('district_id');
            $table->float('shipping_charge', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['delivery_zone_id', 'district_id']);
        });

        Schema::table('generals', function (Blueprint $table) {
            $table->string('bangla_qr_number', 100)->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('advance_status', 15)->nullable()->comment('pending, verified, rejected');
            $table->timestamp('advance_verified_at')->nullable();
            $table->integer('advance_verified_by')->nullable();
            $table->string('advance_note', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_zone_charges');

        Schema::table('generals', function (Blueprint $table) {
            $table->dropColumn('bangla_qr_number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['advance_status', 'advance_verified_at', 'advance_verified_by', 'advance_note']);
        });
    }
}
