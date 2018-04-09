<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('type_answer_id')
                ->unsigned()
                ->index()
                ->comment('Type answer: single, multiply');
            $table->foreign('type_answer_id')
                ->references('id')
                ->on('type_answers')
                ->onDelete('cascade');

            $table->integer('test_id')->index()->unsigned();
            $table->foreign('test_id')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade');

            $table->string('text')->comment('Text question');
            $table->integer('count_correct')->comment('Count corrects answers for success passing the test');

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
        Schema::dropIfExists('questions');
    }
}
