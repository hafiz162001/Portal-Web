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
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checkin_beacon_id');
            $table->unsignedBigInteger('checkout_beacon_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bloc_location_id');
            $table->foreign('checkin_beacon_id')->references('id')->on('beacons')->onUpdate('cascade');
            $table->foreign('checkout_beacon_id')->references('id')->on('beacons')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('bloc_location_id')->references('id')->on('bloc_locations')->onUpdate('cascade');
            $table->dateTime('checkin_at')->nullable();
            $table->dateTime('checkout_at')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
};
