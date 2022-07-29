<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('survey', function(Blueprint $table) {
            $table->tinyInteger('klasifikasi')->default(0)->after('is_reusable');
            $table->tinyInteger('skor')->default(0)->after('is_reusable');
        });

        Schema::create('klasifikasi_skor', function(Blueprint $table) {
            $table->increments('id');
            $table->string('klasifikasi',50);
            $table->integer('survey_id');
            $table->char('logika',5);
            $table->integer('nilai_a');
            $table->integer('nilai_b');
            $table->softDeletes();
            $table->timeStamps();
        });

        Schema::create('multisurvey', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('interviewer_id');
            $table->integer('survey_id');
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
        Schema::table('survey', function(Blueprint $table) {
            $table->dropColumn('klasifikasi');
            $table->dropColumn('skor');
        });

        Schema::dropIfExists('klasifikasi_skor');

        Schema::dropIfExists('multisurvey');
    }
}
