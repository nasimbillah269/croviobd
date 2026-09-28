<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('permission_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('first_name', 150)->nullable();
            $table->string('last_name', 150)->nullable();
            $table->string('full_name', 250)->nullable();
            $table->string('email', 200)->nullable()->unique();
            $table->string('mobile', 100)->nullable()->unique();
            $table->text('profile')->nullable();
            $table->string('company_name', 200)->nullable();
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->string('postal_address', 250)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->integer('city')->nullable();
            $table->integer('district')->nullable();
            $table->integer('division')->nullable();
            $table->integer('country')->nullable();
            $table->string('city_name', 200)->nullable();
            $table->string('area_name', 200)->nullable();
            $table->timestamp('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=Inactive, 1=Active, 2=draft');
            $table->boolean('fetured')->default(0)->comment('0=no fetured, 1=Fetured');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('password2', 200)->nullable();
            $table->string('password_show', 191)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('api_token', 100)->nullable();
            $table->string('device_key')->nullable();
            $table->string('verify_code', 100)->nullable();
            $table->boolean('verify_code_status')->default(0);
            $table->string('reset_remember', 100)->nullable();
            $table->string('designation', 200)->nullable();
            $table->float('balance', 10, 2)->default(0);
            $table->boolean('subscriber')->default(0);
            $table->boolean('customer')->default(1);
            $table->boolean('business')->default(0);
            $table->boolean('employee')->default(0);
            $table->boolean('admin')->default(0);
            $table->integer('old_id')->nullable();
            $table->unsignedBigInteger('addedby_id')->nullable();
            $table->timestamp('addedby_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
