<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable()->default(null);
            $table->unsignedBigInteger('list_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('created_user_id');
            $table->unsignedInteger('order');
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('created_user_id')->references('id')->on('users');
            $table->foreign('list_id')->references('id')->on('lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
