<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRespondenPertanyaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responden', function(Blueprint $table) {
            $table->timestamp('waktu_selesai')->nullable()->after('id');
            $table->timestamp('waktu_mulai')->nullable()->after('id');
            $table->integer('survey_id')->default(0)->after('id');
            $table->dropColumn('nama');
            $table->dropColumn('umur');
            $table->dropColumn('telepon');
            $table->dropColumn('alamat');
            $table->dropColumn('email');
        });


        Schema::create('jawaban_soal_responden', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id');
            $table->integer('responden_id');
            $table->integer('soal_resp_id');
            $table->text('value');
            $table->softDeletes();
            $table->timeStamps();
        });

        Schema::create('soal_responden', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id');
            $table->string('tipe',30);
            $table->tinyInteger('is_required')->default(1);
            $table->text('label');
            $table->text('value')->nullable();
            $table->softDeletes();
            $table->timeStamps();
        });

        Schema::table('survey', function(Blueprint $table) {
            $table->tinyInteger('soal_resp')->default(0)->after('skor');
        });


        DB::statement(' CREATE OR REPLACE VIEW view_survey
            AS SELECT s.id, s.nama, s.durasi, s.soal_resp, COUNT(v.responden_id) as total_responden
            FROM survey as s  
            LEFT OUTER JOIN view_survey_responden as v ON s.id = v.survey_id
            WHERE s.is_reusable = 0 AND s.deleted_at IS NULL
            GROUP BY s.id, s.nama, s.durasi, s.soal_resp
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responden', function(Blueprint $table) {
            $table->dropColumn('waktu_selesai');
            $table->dropColumn('waktu_mulai');
            $table->dropColumn('survey_id');
            $table->string('nama',50);
            $table->string('telepon',15);
            $table->integer('umur');
            $table->string('alamat',150);
            $table->string('email',50);
        });

        Schema::dropIfExists('jawaban_soal_responden');

        Schema::dropIfExists('soal_responden');

        Schema::table('survey', function(Blueprint $table) {
            $table->dropColumn('soal_resp');
        });
    }
}
