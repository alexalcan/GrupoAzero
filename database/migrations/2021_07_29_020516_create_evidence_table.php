<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->id();

            $table->string('file');

            $table->unsignedBigInteger('cancelation_id')->nullable();
            $table->foreign('cancelation_id')->references('id')->on('cancelations')->onUpdate('cascade');

            $table->unsignedBigInteger('rebilling_id')->nullable();
            $table->foreign('rebilling_id')->references('id')->on('rebillings')->onUpdate('cascade');

            $table->unsignedBigInteger('debolution_id')->nullable();
            $table->foreign('debolution_id')->references('id')->on('debolutions')->onUpdate('cascade');

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
        Schema::dropIfExists('evidence');
    }
}
