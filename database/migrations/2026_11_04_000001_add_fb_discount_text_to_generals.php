<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFbDiscountTextToGenerals extends Migration
{
    public function up()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->text('fb_discount_text')->nullable()->comment('offer text shown above the link field at checkout');
        });
    }

    public function down()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->dropColumn('fb_discount_text');
        });
    }
}
