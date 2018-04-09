<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_require')->default(true);
            $table->time('time_passing')->comment('Testing time');
            $table->boolean('is_random')->default(true)->comment('In order ?');
            $table->integer('count_attemps')->comment('Number of attempts');
            $table->integer('count_correct')->comment('Number of correct answers');
            $table->boolean('is_success')->default(false);

            $table->integer('lesson_id')->index()->unsigned();
            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('cascade');

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
        Schema::dropIfExists('tests');
    }
}
