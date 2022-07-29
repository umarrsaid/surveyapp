<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement( 'DROP VIEW IF EXISTS view_pertanyaan_pilihan' );
        // DB::statement( 'DROP VIEW IF EXISTS view_pertanyaan_jenis' );
        // DB::statement( 'DROP VIEW IF EXISTS view_rule_data' );
        // DB::statement( 'DROP VIEW IF EXISTS view_anti_data' );
        
        DB::statement(' CREATE OR REPLACE VIEW view_pertanyaan_pilihan
                        AS SELECT ps.*,p.text AS pertanyaan, pil.id AS pilihan_id, pil.text
                        FROM pertanyaan_survey AS ps
                        JOIN pertanyaan AS p
                        JOIN pilihan AS pil
                        WHERE pil.pertanyaan_id = ps.pertanyaan_id
                        AND p.id = ps.pertanyaan_id AND ps.deleted_at IS NULL
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_pertanyaan_jenis
                        AS SELECT ps.id, ps.survey_id, ps.pertanyaan_id, ps.sequence, ps.deleted_at, p.text AS pertanyaan, p.jenis_id, j.nama,pm.is_multiple, p.nomor, ps.static, ps.position
                        FROM pertanyaan_survey AS ps
                        JOIN pertanyaan AS p ON p.id = ps.pertanyaan_id
                        JOIN jenis AS j ON p.jenis_id = j.id
                        LEFT JOIN pilihan_multiple AS pm ON p.id = pm.pertanyaan_id
                        WHERE p.id = ps.pertanyaan_id AND p.jenis_id = j.id AND ps.deleted_at IS NULL
                        -- GROUP BY sequence
                        -- ORDER BY id ASC
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_rule_data
                        AS SELECT r.*, p.text
                        FROM rule AS r
                        JOIN pilihan AS p 
                        ON p.id = r.pilihan_id 
                        WHERE r.deleted_at IS NULL 
                    ');
        DB::statement(' CREATE OR REPLACE VIEW view_anti_data
                        AS SELECT a.id, a.pertanyaan_id, a.pilihan_id, a.pilihan_id_anti, a.deleted_at, p.text as pilihan, pil.text as pilihan_anti
                        FROM anti_pair as a
                        JOIN pilihan as p
                        JOIN pilihan as pil
                        ON a.pilihan_id = p.id AND a.pilihan_id_anti = pil.id
                        WHERE a.deleted_at IS NULL
                    ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement( 'DROP VIEW view_pertanyaan_pilihan, view_pertanyaan_jenis, view_rule_data, view_anti_data' );
        // DB::statement( 'DROP VIEW view_pertanyaan_pilihan' );
        // DB::statement( 'DROP VIEW ' );
        // DB::statement( 'DROP VIEW ' );
        // DB::statement( 'DROP VIEW ' );

    }
}
