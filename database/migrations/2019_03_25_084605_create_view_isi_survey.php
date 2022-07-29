<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewIsiSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement( 'DROP VIEW IF EXISTS view_survey_isi' );
        // DB::statement( 'DROP VIEW IF EXISTS view_total_jawaban' );
        // DB::statement( 'DROP VIEW IF EXISTS view_survey_responden' );
        // DB::statement( 'DROP VIEW IF EXISTS view_responden' );
        // DB::statement( 'DROP VIEW IF EXISTS view_responden_detail' );
        // DB::statement( 'DROP VIEW IF EXISTS view_responden_pertanyaan_detail' );
        // DB::statement( 'DROP VIEW IF EXISTS view_survey' );


        DB::statement(' CREATE OR REPLACE VIEW view_survey_isi
                        AS (SELECT ps.survey_id, i.responden_id
                        FROM pertanyaan_survey as ps
                        JOIN isian AS i
                        ON i.pertanyaan_id = ps.id)
                        UNION ALL 
                        (SELECT ps.survey_id, j.responden_id
                        FROM pertanyaan_survey as ps
                        JOIN jawaban as j 
                        ON j.pertanyaan_id = ps.id)
                        UNION ALL 
                        (SELECT ps.survey_id, psc.responden_id
                        FROM pertanyaan_survey as ps
                        JOIN pertanyaan_scale as psc 
                        ON psc.pertanyaan_id = ps.id)
                    ');

        DB::statement(' CREATE OR REPLACE VIEW view_total_jawaban
                        AS SELECT survey_id, responden_id, COUNT(responden_id) as total_jawaban FROM 
                        view_survey_isi
                        GROUP BY survey_id, responden_id
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_survey_responden
                        AS SELECT * FROM view_survey_isi
                        GROUP BY survey_id, responden_id
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_responden
                        AS SELECT vsr.survey_id, vsr.responden_id, r.nama, r.telepon, r.umur, r.alamat, r.email FROM view_survey_responden as vsr 
                        JOIN responden as r 
                        ON r.id = vsr.responden_id
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_responden_detail
                        AS (SELECT ps.survey_id, ps.sequence, ps.static, i.responden_id, i.pertanyaan_id, i.text, null as pilihan, null as value
                        FROM pertanyaan_survey as ps
                        JOIN isian AS i
                        ON i.pertanyaan_id = ps.id) 
                        UNION ALL 
                        (SELECT ps.survey_id, ps.sequence, ps.static, j.responden_id, j.pertanyaan_id, null as text, j.pilihan_id, null as value
                        FROM pertanyaan_survey as ps
                        JOIN jawaban as j 
                        ON j.pertanyaan_id = ps.id)
                        UNION ALL 
                        (SELECT ps.survey_id, ps.sequence, ps.static, psc.responden_id, psc.pertanyaan_id, null as text, null as pilihan, psc.value
                        FROM pertanyaan_survey as ps
                        JOIN pertanyaan_scale as psc 
                        ON psc.pertanyaan_id = ps.id)
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_responden_pertanyaan_detail
                        AS SELECT v.*, p.nomor, p.text as text_pertanyaan FROM view_responden_detail as v
                        JOIN pertanyaan as p 
                        ON p.id = v.pertanyaan_id
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_survey
                        AS SELECT s.id, s.nama, s.durasi, COUNT(v.responden_id) as total_responden
                        FROM survey as s  
                        LEFT OUTER JOIN view_survey_responden as v ON s.id = v.survey_id
                        WHERE s.is_reusable = 0 AND s.deleted_at IS NULL
                        GROUP BY s.id, s.nama, s.durasi


                    ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('view_survey_isi', function (Blueprint $table) {
            //
        // });
    }
}
