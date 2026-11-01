<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFacebookPostDiscount extends Migration
{
    public function up()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->boolean('fb_discount_status')->default(0);
            $table->string('fb_discount_type', 20)->default('flat')->comment('flat, percentage');
            $table->float('fb_discount_amount', 10, 2)->default(0);
            $table->float('fb_discount_max', 10, 2)->default(0)->comment('0 = no limit, for percentage');
            $table->integer('fb_post_min_days')->default(15);
            $table->string('fb_full_advance_rule', 20)->default('refund')->comment('refund, not_allowed');
            $table->string('fb_page_name', 100)->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('fb_post_link', 500)->nullable();
            $table->string('fb_post_key', 191)->nullable()->index()->comment('normalised link used to stop re-use');
            $table->string('fb_post_status', 15)->nullable()->comment('pending, approved, rejected');
            $table->float('fb_discount', 10, 2)->default(0);
            $table->string('fb_note', 255)->nullable();
            $table->timestamp('fb_reviewed_at')->nullable();
            $table->integer('fb_reviewed_by')->nullable();
        });
    }

    public function down()
    {
        Schema::table('generals', function (Blueprint $table) {
            $table->dropColumn(['fb_discount_status', 'fb_discount_type', 'fb_discount_amount', 'fb_discount_max', 'fb_post_min_days', 'fb_full_advance_rule', 'fb_page_name']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['fb_post_link', 'fb_post_key', 'fb_post_status', 'fb_discount', 'fb_note', 'fb_reviewed_at', 'fb_reviewed_by']);
        });
    }
}
