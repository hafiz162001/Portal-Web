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
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->date('selected_date');
            $table->integer('amount')->nullable()->default(1);
            $table->integer('status')->nullable()->default(0);
            $table->integer('total_price')->nullable()->default(0);
            $table->unsignedBigInteger('visitor_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('event_ticket_id')->references('id')->on('event_tickets')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('visitor_id')->references('id')->on('visitors')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('user_apps')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('user_apps')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_orders');
    }
};
