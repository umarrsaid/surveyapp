<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(' CREATE OR REPLACE VIEW view_api_pertanyaan
                        AS SELECT ps.id, ps.pertanyaan_id,p.text, ps.sequence,ps.survey_id,j.id as jenis_id, j.nama, j.is_multiple, p.nomor, ps.static, ps.position
                        FROM pertanyaan_survey AS ps
                        JOIN pertanyaan AS p ON p.id = ps.pertanyaan_id
                        JOIN jenis AS j ON j.id = p.jenis_id
                        WHERE ps.deleted_at IS NULL AND p.deleted_at IS NULL
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
