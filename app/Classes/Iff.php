<?php 
namespace App\Classes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class Iff {

	public static function index(){
		return 'tes class';
	}

    public static function jenis($id = null){
        $where = '';
        if ($id != null) {
            $id_enkrip = Crypt::decryptString($id);
            $data = \DB::table('survey')->where('id',$id_enkrip)->first();
            if ($data->is_reusable == 2) {
                $where = ' where id IN (2,3,7)';
            }
        }
        $jenis = \DB::select('select * from jenis'.$where);
        return $jenis;
    }

    public static function jenisReusable(){
        $jenis = \DB::select('select s.id, s.nama, ps.pertanyaan_id from survey as s 
                    LEFT JOIN pertanyaan_survey as ps 
                    ON ps.survey_id = s.id
                    where s.deleted_at IS NULL and ps.deleted_at IS NULL and ps.pertanyaan_id IS NOT NULL and is_reusable = "1"
                    ');
        return $jenis;
    }

    public static function currencyLap($cur, $val)//Format Uang
    {
        $r = '';
        switch ($cur) {
            case 'rp' :
            $r = number_format($val, 2, ',', '.');
            break;
        }
        return $r;
    }

	public static function namaSurvey($id){
        $id_enkrip = Crypt::decryptString($id);
        $data = \DB::table('survey')->where('id',$id_enkrip)->first();
        if ($data->nama != null) {
            return $data->nama;
        }else{
            return '';
        }
    }


    public static function namaSurvey2($id){
        $data = \DB::table('survey')->where('id',$id)->first();
        if ($data->nama != null) {
            return $data->nama;
        }else{
            return '';
        }
    }

    public static function namaPertanyaan($id){
        $data = \DB::table('pertanyaan')->where('id',$id)->first();
        if ($data->text != null) {
            return $data->text;
        }else{
            return '';
        }
    }

    public static function namaInstruksi($id){
        $data = \DB::table('instruksi')->where('pertanyaan_id',$id)->first();
        if ($data->text != null) {
            return $data->text;
        }else{
            return '';
        }
    }

    public static function getAllSurvey(){
        $survey = \DB::select(\DB::raw('SELECT id, nama FROM survey WHERE deleted_at IS NULL'));
        // if (isset($survey)) {
            return $survey;
        // }else{
            // return '';
        // }
    }

    public static function getStatic($id){
        $survey = \DB::select(\DB::raw('SELECT id, pertanyaan_id, static FROM pertanyaan_survey WHERE deleted_at IS NULL AND  pertanyaan_id = '.$id.' LIMIT 1'));
        if (isset($survey)) {
            return $survey[0]->static;
        }else{
            return '';
        }
    }

    public static function getNomorPertanyaan($id){
        $data = \DB::select(\DB::raw('SELECT nomor FROM pertanyaan WHERE id = '.$id.' LIMIT 1'));
        return $data[0]->nomor;
    }

    public static function namaJenis($id){
        $jenis = \DB::table('jenis')->where('id',$id)->first();
        return $jenis->nama;
    }

    public static function getPilihan($id){
        $data = \DB::select(\DB::raw('SELECT text FROM pilihan WHERE id = '.$id.' LIMIT 1'));
        return $data[0]->text;
    }
}