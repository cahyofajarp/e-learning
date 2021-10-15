<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClassroomLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_lesson', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();
            
            $table->foreign('classroom_id')->references('id')->on('classrooms')
            ->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')
            ->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')
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
        Schema::dropIfExists('classroom_lesson');
    }
}
