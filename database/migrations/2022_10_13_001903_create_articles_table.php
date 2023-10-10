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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('publisher')->nullable();
            $table->string('slug')->nullable();
            $table->string('creator')->nullable();
            $table->string('category_name')->nullable();
            $table->string('channel_name')->nullable();
            $table->date('publish_date')->nullable();
            $table->date('unpublish_date')->nullable();
            $table->text('descriptions')->nullable();
            $table->text('keterangan_gambar')->nullable();
            $table->text('sumber_gambar')->nullable();
            $table->biginteger('category_id')->nullable();
            $table->biginteger('channel_id')->nullable();
            $table->string('tag_id')->nullable();
            $table->string('status')->nullable();
            $table->string('gambar')->nullable();
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
        Schema::dropIfExists('articles');
    }
};
