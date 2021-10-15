<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Materialworks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materialworks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('status');
            $table->string('material_file');
            $table->unsignedBigInteger('work_id');
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
        Schema::dropIfExists('materialworks');
    }
}
