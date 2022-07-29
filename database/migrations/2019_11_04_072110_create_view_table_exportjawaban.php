<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewTableExportjawaban extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW view_export_soal
            AS SELECT ps.survey_id, ps.pertanyaan_id, p.`text`, p.jenis_id, p.nomor, IF(p.jenis_id = 3, 1,pm.is_multiple) AS is_multiple, 
        group_concat(pil.`text` SEPARATOR '|=|') AS pilihan,group_concat(pil.bobot SEPARATOR '|=|') AS bobot,group_concat(pil.id SEPARATOR '|=|') AS pilihan_id
        FROM pertanyaan_survey ps
        LEFT JOIN pertanyaan p ON p.id = ps.pertanyaan_id
        LEFT OUTER JOIN pilihan pil ON pil.pertanyaan_id = p.id
        LEFT OUTER JOIN pilihan_multiple pm ON pm.pertanyaan_id = p.id
        WHERE ps.deleted_at IS NULL AND p.deleted_at IS NULL AND pil.deleted_at IS NULL
        GROUP BY ps.pertanyaan_id, ps.survey_id, p.`text`, p.jenis_id, p.nomor, pm.is_multiple
        ORDER BY p.id ASC
        ");


        DB::statement(' CREATE OR REPLACE VIEW view_survey_reusable
        AS SELECT s.id, s.nama, s.durasi, s.klasifikasi, s.skor, s.soal_resp, COUNT(v.responden_id) as total_responden
        FROM survey as s  
        LEFT OUTER JOIN view_survey_responden as v ON s.id = v.survey_id
        WHERE s.is_reusable = 2 AND s.deleted_at IS NULL
        GROUP BY s.id, s.nama, s.durasi, s.klasifikasi, s.skor, s.soal_resp
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
