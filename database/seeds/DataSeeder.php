<?php

use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Interviewer
        \DB::table('interviewer')->insert([
        	'nama' => 'userinterview12'
        ]);

        //Device
        \DB::table('device')->insert([
        	'nama' => 'Xiaomi Redmi 4A',
        	'imei' => '10001928665AD',
        	'keterangan' => '-',
        	'survey_id' => 1
        ]);

        //Survey
        \DB::table('survey')->insert([
        	'nama' => 'Sample Survey',
        	'durasi' => 1,
        	'is_reusable' => 0,
        	'user_id' => 2
        ]);

        //Pertanyaan
        \DB::table('pertanyaan')->insert([
        	'text' => 'Hello World',
        	'jenis_id' => 1,
        	'nomor' => 111111111,
        ]);

        //Pilihan
        \DB::table('pilihan')->insert([
        	[
	        	'text' => 'Ya',
	        	'pertanyaan_id' => 1
        	],
        	[
        		'text' => 'Tidak',
        		'pertanyaan_id' => 1
        	]
        ]);
        //Pertanyaan Survey
        \DB::table('pertanyaan_survey')->insert([
        	'survey_id' => 1,
        	'pertanyaan_id' => 1,
        	'sequence' => 1,
        	'static' => '<p>Sample</p>',
        	'position' => 'Atas'
        ]);

        //Responden
        \DB::table('responden')->insert([
        	'nama' => 'SampleResponden',
        	'telepon' => '081xxxxxxxxx',
        	'umur' => 21,
        	'alamat' => 'Bandung',
        	'email' => 'sample@gmail.com'
        ]);

        //Jawaban
        \DB::table('jawaban')->insert([
        	'pilihan_id' => 1,
        	'pertanyaan_id' => 1,
        	'responden_id' => 1,
        ]);
    }
}