<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('test_code');
            $table->string('decription');
            $table->string('standard');
            $table->string('time');
            $table->string('status')->default('0');
            $table->timestamp('start_test')->nullable();
            $table->timestamp('deadline_test')->nullable();

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();

            $table->timestamps();

            $table->foreign('teacher_id')->references('id')
            ->on('teachers')
            ->onDelete('cascade');

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
        Schema::dropIfExists('tests');
    }
}
