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
        Schema::create('service_points', function (Blueprint $table) {
            $table->id();
            $table->integer('serviceID');
            $table->string('servicePointTitle')->nullable();
            $table->text('servicePointDescription')->nullable();
            $table->mediumText('servicePointAttachmentSrc')->nullable(); // 16Megabytes max
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
        Schema::dropIfExists('service_points');
    }
};
