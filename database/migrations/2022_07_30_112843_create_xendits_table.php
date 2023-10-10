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
        Schema::create('xendits', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->string('payment_channel');
            $table->string('email');
            $table->double('price');
            $table->integer('status')->default(0);
            $table->text("request");
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
        Schema::dropIfExists('xendits');
    }
};
