<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFbDiscountTermsToGenerals extends Migration
{
    public function up()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->text('fb_discount_terms')->nullable()->comment('terms shown below the post type at checkout; supports {post} {reels} {page} {days}');
        });
    }

    public function down()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->dropColumn('fb_discount_terms');
        });
    }
}
