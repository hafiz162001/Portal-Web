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
        Schema::table('user_apps', function (Blueprint $table) {
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('active_ticket_order_id')->nullable();
            $table->foreign('active_ticket_order_id')->references('id')->on('ticket_orders')->onUpdate('cascade');
        });

        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dateTime('ticket_used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_apps', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->dropColumn('active_ticket_order_id');
        });
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropColumn('ticket_used_at');
        });
    }
};
