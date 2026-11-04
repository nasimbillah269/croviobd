<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFbReelsDiscount extends Migration
{
    public function up()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->float('fb_reels_discount_amount', 10, 2)->default(0)->comment('flat discount for a Reels; fb_discount_amount is for a photo post');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('fb_post_type', 10)->nullable()->comment('post, reels');
        });
    }

    public function down()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->dropColumn('fb_reels_discount_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('fb_post_type');
        });
    }
}
