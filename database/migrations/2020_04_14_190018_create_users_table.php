<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            //$table->id();
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('gender')->nullable(true)->default(null);
            $table->string('phone')->unique()->nullable(false);
            $table->string('email')->unique()->nullable(true)->default(null);
            $table->string('password')->nullable(true)->default(null);
            $table->string('avatar')->nullable(true)->default(null);
            $table->boolean('isPhoneVerified')->default(false);
            $table->boolean('isEmailVerified')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
