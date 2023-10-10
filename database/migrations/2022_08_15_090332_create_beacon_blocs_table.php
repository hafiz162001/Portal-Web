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
        // Schema::create('beacon_blocs', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('bloc_location_id');
        //     $table->foreign('bloc_location_id')->references('id')->on('bloc_locations')->onUpdate('cascade');
        //     $table->unsignedBigInteger('beacon_id');
        //     $table->foreign('beacon_id')->references('id')->on('beacons')->onUpdate('cascade');
        //     $table->timestamps();
        //     $table->unsignedBigInteger('created_by')->nullable();
        //     $table->unsignedBigInteger('updated_by')->nullable();
        //     $table->unsignedBigInteger('deleted_by')->nullable();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beacon_blocs');
    }
};
