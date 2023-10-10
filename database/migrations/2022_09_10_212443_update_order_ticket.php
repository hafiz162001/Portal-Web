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
            $table->unsignedBigInteger('active_event_id')->nullable();
            $table->foreign('active_event_id')->references('id')->on('events')->onUpdate('cascade');
        });
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->boolean('isUsed')->nullable()->default(0);
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
            $table->dropColumn('isUsed');
        });
        Schema::table('user_apps', function (Blueprint $table) {
            $table->dropColumn('active_event_id');
        });
    }
};
