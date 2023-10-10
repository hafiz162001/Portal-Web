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
        Schema::create('user_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->boolean('isCheckin')->default(false);
            $table->boolean('isRegistered')->default(false);
            $table->tinyInteger('role')->default(1)->nullable()->comment('[0 => security, 1 => pengunjung]');
            $table->unsignedBigInteger('bloc_location_id')->nullable()->comment('for security job location');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('bloc_location_id')->references('id')->on('bloc_locations')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_apps');
    }
};
