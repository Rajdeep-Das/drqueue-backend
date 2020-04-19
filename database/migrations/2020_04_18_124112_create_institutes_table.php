<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('superadmin')->nullable(false);
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('postcode');
            $table->string('landmark')->nullable(true)->default(null);
            $table->string('lat')->nullable(true)->default(null);
            $table->string('lon')->nullable(true)->default(null);
            $table->string('website')->nullable(true)->default(null);
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
        Schema::dropIfExists('institutes');
    }
}
