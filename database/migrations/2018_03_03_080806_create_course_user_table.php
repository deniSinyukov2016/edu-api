<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('course_user', function (Blueprint $table) {
            $table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');

            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->timestamp('start_time')->nullable();
            $table->timestamp('close_time')->nullable();

            $table->integer('course_status_id')->unsigned()->index()->nullable();
            $table->foreign('course_status_id')
                  ->references('id')
                  ->on('course_statuses')
                  ->onDelete('cascade');

            $table->primary(['course_id', 'user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_status');
        Schema::dropIfExists('course_user');
    }
}
