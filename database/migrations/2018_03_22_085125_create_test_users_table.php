<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestUsersTable extends Migration
{

    public function up()
    {
        Schema::create('test_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('test_id')->unsigned()->index();
            $table->foreign('test_id')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade');

            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('count_attemps')->default(0);

            $table->boolean('is_success')->default(false);

            $table->primary(['user_id', 'test_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_users');
    }
}
