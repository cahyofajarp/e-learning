<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherStudentIdOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable()->after('remember_token');
            $table->unsignedBigInteger('student_id')->nullable()->after('remember_token');

            $table->foreign('student_id')->references('id')
            ->on('students')
            ->onDelete('cascade');

            $table->foreign('teacher_id')->references('id')
            ->on('teachers')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('teacher_id');
            $table->dropForeign('student_id');
        });
    }
}
