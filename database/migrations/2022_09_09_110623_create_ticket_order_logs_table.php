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
        Schema::create('ticket_order_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_order_id');
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->softDeletes();
            $table->foreign('ticket_order_id')->references('id')->on('ticket_orders')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->string('invoice_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
        });
        Schema::dropIfExists('ticket_order_logs');
    }
};
