<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClassroomTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_test', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->unsignedBigInteger('test_id')->nullable();
            $table->timestamps();

            
            $table->foreign('classroom_id')->references('id')
            ->on('classrooms')
            ->onDelete('cascade');

            $table->foreign('test_id')->references('id')
            ->on('tests')
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
        Schema::dropIfExists('classroom_test');
    }
}
