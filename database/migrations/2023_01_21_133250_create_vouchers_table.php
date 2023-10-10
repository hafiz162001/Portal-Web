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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_apps_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('voucher_code')->nullable();
            $table->integer('amount')->nullable();
            $table->string('image')->nullable();
            $table->smallInteger('status')->nullable();
            $table->date('started_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->date('claimed_date')->nullable();
            $table->date('redeemed_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
};
