<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetsAudienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_audiences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('course_target_audience', function (Blueprint $table) {
            $table->integer('target_audience_id')->unsigned()->index();
            $table->foreign('target_audience_id')->references('id')->on('target_audiences')->onDelete('cascade');

            $table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->primary(['target_audience_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_audiences');
        Schema::dropIfExists('course_target_audience');

    }
}
