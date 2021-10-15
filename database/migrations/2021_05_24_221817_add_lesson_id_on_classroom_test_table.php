<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonIdOnClassroomTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_test', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable();

            $table->foreign('lesson_id')->references('id')
            ->on('lessons')
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
        Schema::table('classroom_test', function (Blueprint $table) {
            $table->dropForeign('classroom_test_lesson_id_foreign');
            $table->dropColumn('lesson_id');

            
        });
    }
}
