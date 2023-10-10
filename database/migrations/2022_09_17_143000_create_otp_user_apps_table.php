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
        Schema::create('otp_user_apps', function (Blueprint $table) {
            $table->id();
            $table->string('phone');            
            $table->string('code');            
            $table->string('message');            
            $table->json('response');            
            $table->boolean('isUsed')->nullable()->default(false);            
            $table->timestamps();
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
        Schema::dropIfExists('otp_user_apps');
    }
};
