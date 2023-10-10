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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('isFeatured')->nullable()->default(false);
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->boolean('isRecommended')->nullable()->default(false);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('isFeatured');
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('isRecommended');
        });
    }
};
