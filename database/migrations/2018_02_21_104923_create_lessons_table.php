<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();

            $table->integer('type_lessons_id')->unsigned()->index();
            $table->foreign('type_lessons_id')
                  ->references('id')
                  ->on('type_lessons')
                  ->onDelete('cascade');

            $table->integer('module_id')
                  ->nullable()
                  ->unsigned()
                  ->index();
            $table->foreign('module_id')
                  ->references('id')
                  ->on('modules')
                  ->onDelete('cascade');

            $table->integer('course_id')
                  ->unsigned()
                  ->index();
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');

            $table->timestamps();
        });
        Schema::create('lesson_user', function (Blueprint $table) {
            $table->integer('lesson_id', false, true)->index();
            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('cascade');

            $table->integer('user_id', false, true)->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->boolean('is_close')->default(true);
            $table->boolean('is_complete')->default(false);
//            $table->integer('order', false, true)->default(0);

            $table->primary(['lesson_id', 'user_id']);

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
        Schema::dropIfExists('lesson_user');
        Schema::dropIfExists('lessons');
    }
}
