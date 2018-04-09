<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('fileable_id');
            $table->string('fileable_type');
            $table->string('file')->nullable();
            $table->string('type')->nullable();
            $table->double('size')->nullable();
            $table->string('original_name')->nullable();
            $table->boolean('is_sertificate')->default(false);
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
        Schema::dropIfExists('files');
    }
}
