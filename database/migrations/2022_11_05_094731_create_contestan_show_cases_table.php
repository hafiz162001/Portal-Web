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
        Schema::create('contestan_show_cases', function (Blueprint $table) {
            $table->id();
            $table->integer('contestan_id');
            $table->integer('user_apps_id');
            $table->integer('album_id')->nullable();
            $table->string('title')->nullable();
            $table->string('singer')->nullable();
            $table->string('writen')->nullable();
            $table->string('produce')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->smallinteger('status')->default(0);
            $table->integer('num_of_partition')->default(1);
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
        Schema::dropIfExists('contestan_show_cases');
    }
};
