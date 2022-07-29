<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRequiredjwb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pilihan', function(Blueprint $table) {
            $table->tinyInteger('is_required')->default(0)->after('pertanyaan_id');
        });

        Schema::table('pertanyaan_survey', function(Blueprint $table) {
            $table->tinyInteger('is_klasifikasi')->default(0)->after('position');
        });

        DB::statement('CREATE OR REPLACE VIEW view_pertanyaan_pilihan
        AS SELECT ps.*,p.text AS pertanyaan, pil.id AS pilihan_id, pil.text
        FROM pertanyaan_survey AS ps
        JOIN pertanyaan AS p
        JOIN pilihan AS pil
        WHERE pil.pertanyaan_id = ps.pertanyaan_id
        AND p.id = ps.pertanyaan_id AND ps.deleted_at IS NULL AND pil.deleted_at IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pilihan', function(Blueprint $table) {
            $table->dropColumn('is_required');
        });
        Schema::table('pertanyaan_survey', function(Blueprint $table) {
            $table->dropColumn('is_klasifikasi');
        });
    }
}
