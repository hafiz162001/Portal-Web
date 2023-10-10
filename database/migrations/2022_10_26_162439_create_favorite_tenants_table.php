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
        Schema::create('favorite_tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('location_id');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('cascade');
        });
        Schema::table('event_tickets', function (Blueprint $table) {
            $table->string('type')->nullable()->default('pengunjung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_tenants');
        Schema::table('event_tickets', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
