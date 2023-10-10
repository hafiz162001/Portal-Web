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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('order');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('image')->nullable();
            $table->string('period_of_use'); // need to be fixed
            $table->tinyInteger('usage')->comment('[0 => online, 1 => offline]'); // need to be fixed
            $table->text('description');
            $table->text('term_and_condition');
            $table->timestamps();
            $table->smallInteger('active')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('promos');
    }
};
