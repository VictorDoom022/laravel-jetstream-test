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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('serviceName');
            $table->string('serviceCategoryID');
            $table->string('serviceFirstContentTitle')->nullable();
            $table->text('serviceFirstContentDescription')->nullable();
            $table->string('serviceFirstContentAttachment')->nullable();
            $table->string('serviceSecondContentTitle')->nullable();
            $table->text('serviceSecondContentDescription')->nullable();
            $table->string('serviceSecondContentAttachment')->nullable();
            $table->string('servicePointTitle')->nullable();
            $table->string('serviceBottomTitle')->nullable();
            $table->text('serviceBottomDescription')->nullable();

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
        Schema::dropIfExists('services');
    }
};
