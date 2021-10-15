<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answerworks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('score');
            $table->unsignedBigInteger('materialwork_id');
            $table->timestamps();

            
            $table->foreign('materialwork_id')->references('id')
            ->on('materialworks')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answerworks');
    }
}
