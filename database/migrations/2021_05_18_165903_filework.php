<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Filework extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filework', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_id');
            $table->string('file');
            $table->timestamps();

            $table->foreign('work_id')->references('id')
            ->on('works')
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
        Schema::dropIfExists('filework');
    }
}
