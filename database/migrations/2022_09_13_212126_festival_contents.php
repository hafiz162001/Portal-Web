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
        Schema::create('festival_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('star')->nullable();
            $table->string('quotes')->nullable();
            $table->string('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('file')->nullable();
            $table->integer('festival_id')->nullable();
            $table->integer('festival_category_id')->nullable();
            $table->string('festival_category_name')->nullable();
            $table->integer('festival_content_category_id')->nullable();
            $table->string('festival_content_category_name')->nullable();
            $table->integer('festival_genre_id')->nullable();
            $table->string('festival_genre_name')->nullable();
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
        Schema::dropIfExists('festival_category_contents');
    }
};
