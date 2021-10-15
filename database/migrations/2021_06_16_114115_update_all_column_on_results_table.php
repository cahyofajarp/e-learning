<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAllColumnOnResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->unsignedBigInteger('test_id')->unsigned()->nullable()->change();
            $table->unsignedBigInteger('student_id')->unsigned()->nullable()->change();
            $table->string('slug')->nullable()->change();
            $table->string('jml_soal')->nullable()->change();
            $table->string('terjawab')->nullable()->change();
            $table->string('tidak_terjawab')->nullable()->change();
            $table->string('jml_benar')->nullable()->change();
            $table->string('score')->nullable()->change();
            $table->string('status')->nullable()->change();

            $table->timestamp('start_test')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
       
        });
    }
}
