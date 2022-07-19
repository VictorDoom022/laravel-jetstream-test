<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage_service', function (Blueprint $table) {
            $table->id();
            $table->string('homepageServiceTitle')->nullable();
            $table->integer('homepageService1')->nullable();
            $table->integer('homepageService2')->nullable();
            $table->integer('homepageService3')->nullable();
            $table->integer('homepageService4')->nullable();
            $table->string('homepageServiceImage')->nullable();
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
        Schema::dropIfExists('homepage_service');
    }
};
