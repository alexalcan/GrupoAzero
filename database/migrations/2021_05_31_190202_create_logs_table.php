<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->string('status');
            $table->string('action');

            $table->unsignedBigInteger('order_id')->default(3);
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');

            $table->unsignedBigInteger('user_id')->default(3);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');

            $table->unsignedBigInteger('department_id')->default(3);
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade');

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
        Schema::dropIfExists('logs');
    }
}
