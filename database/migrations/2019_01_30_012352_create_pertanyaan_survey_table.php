<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePertanyaanSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertanyaan_survey', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id');
            $table->integer('pertanyaan_id');
            $table->integer('sequence')->nullable();
            // $table->integer('sequence_reusable')->nullable();
            $table->text('static')->nullable();
            $table->string('position',10)->nullable();
            $table->integer('rec_usr')->nullable();
            $table->softDeletes();
            $table->timeStamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pertanyaan_survey');
    }
}
