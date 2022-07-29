<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GantiViewPertanyaanJenis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(' CREATE OR REPLACE VIEW view_pertanyaan_jenis
            AS SELECT ps.id, ps.survey_id, ps.pertanyaan_id, ps.sequence, ps.deleted_at, p.text AS pertanyaan, p.jenis_id, j.nama,pm.is_multiple,pm.id as pm_id, p.nomor, ps.static, ps.position
            FROM pertanyaan_survey AS ps
            JOIN pertanyaan AS p ON p.id = ps.pertanyaan_id
            JOIN jenis AS j ON p.jenis_id = j.id
            LEFT JOIN pilihan_multiple AS pm ON p.id = pm.pertanyaan_id
            WHERE p.id = ps.pertanyaan_id AND p.jenis_id = j.id AND ps.deleted_at IS NULL
            -- GROUP BY sequence
            -- ORDER BY id ASC
        ');

        DB::statement(' CREATE OR REPLACE VIEW view_survey_reusable
        AS SELECT s.id, s.nama, s.durasi, COUNT(v.responden_id) as total_responden
        FROM survey as s  
        LEFT OUTER JOIN view_survey_responden as v ON s.id = v.survey_id
        WHERE s.is_reusable = 1 AND s.deleted_at IS NULL
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
        //
    }
}
