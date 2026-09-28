<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialIdentitiesTable extends Migration
{
    public function up()
    {
        Schema::create('social_identities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('provider_name', 255)->nullable();
            $table->string('provider_id', 255)->nullable()->unique();
            $table->string('provider_token', 255)->nullable()->unique();
            $table->string('provider_img_url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('social_identities');
    }
}
