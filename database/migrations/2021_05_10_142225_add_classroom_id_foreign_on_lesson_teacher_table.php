<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassroomIdForeignOnLessonTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_teacher', function (Blueprint $table) {
            $table->unsignedBigInteger('classroom_id')->nullable();

            $table->foreign('classroom_id')->references('id')
            ->on('classrooms')
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
        Schema::table('lesson_teacher', function (Blueprint $table) {
            $table->dropForeign('lesson_teacher_classroom_id_foreign');
            $table->dropColumn('classroom_id');
        });
    }
}
