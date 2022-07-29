<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Classes\Iff;
use Illuminate\Support\Facades\Crypt;

class PageController extends Controller
{
//     public function index(Request $request){
//     	if ( session('user') != null ) {
//             $token = session('user')[0]->accessToken;
//         }
// 	    	$client = new Client(); //GuzzleHttp\Client
// 			$result = $client->get('/home', [
// 			    'header' => [
// 			        'Authorization' => 'Bearer '.$token,
// 			    ]
// 			]);
//     	// dd($request->all());
//         // $token = session('user')[0]->accessToken;
//         // dd($token);
//         // $response->headers->set('Authorization', 'Bearer '.$token);

//     	// $response ->redirectTo('http://google.com')
// // ->header('Authorization', 'Bearer '.$token);
// 		return $result;
//     	// return view('pages.index');
//     }
    public function home(Request $request){
    	return view('pages.index');
    }
    public function responden($id, Request $request){
        return view('pages.responden.index',compact('id'));
    }
    public function question($id,Request $request){
        return view('pages.question.index',compact('id'));
    }
    public function editQuestion($id,Request $request){
        // $id_enkrip = Crypt::encryptString($id);
        $data = \DB::table('pertanyaan_survey')->where('survey_id',Crypt::encryptString($id))->get();
        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_pilihan where survey_id = '.$id.' order by sequence ASC'));
        // dd($data);
        return view('pages.question.edit',compact('id','data'));
    }
    public function inputScoreSurvey($id,Request $request){
        // $id_enkrip = Crypt::encryptString($id);
        $data = \DB::table('pertanyaan_survey')->where('survey_id',Crypt::encryptString($id))->get();
        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_pilihan where survey_id = '.$id.' order by sequence ASC'));
        // dd($data);
        return view('pages.survey.input_score',compact('id','data'));
    }

    public function survey(Request $request){
        return view('pages.survey.index');
    }

    public function surveyReus(Request $request){
        return view('pages.survey.reusable');
    }
    public function devices(Request $request){
        return view('pages.devices.index');
    }
    public function pertanyaanReusable(Request $request){
        return view('pages.reusable.index');
    }
    public function editPertanyaanReusable($id,Request $request){
        $data = \DB::table('pertanyaan_survey')->where([['survey_id',Crypt::decryptString($id)],['deleted_at',null]])->get();
        return view('pages.reusable.edit',compact('id','data'));
    }
    public function jawaban(Request $request){

        return view('pages.jawaban.index');
    }


    public function user(Request $request)
    {	
        return view('pages.user.index');
    }

    public function interviewer(){
        return view('pages.interviewer.index');
    }

    public function detailJawaban(){
        return view('pages.jawaban.detail');
    }
    
    public function detailSurvey($id)
    {
        return view('pages.detail_survey.index',compact('id'));
    }
    
    public function inputSoalWeb($id)
    {
        $id = Crypt::decryptString($id);
        $datas = \DB::select(\DB::raw('SELECT `sequence`, jenis_id, pertanyaan_id FROM view_api_pertanyaan
        WHERE survey_id = '.$id.'
        order by sequence ASC
            '));
        $data = [];
        foreach ($datas as $key => $value) {
            $data[$value->sequence] = $value;
        }
        $urut = 1;
        $responden = \DB::select("SELECT id, tipe, is_required, label, value FROM soal_responden Where survey_id = $id AND tipe NOT IN ('lokasi','foto') AND deleted_at IS NULL");

        foreach ($data as $key => $value) {

        $pilihan[$value->pertanyaan_id]['instruksi'] = \DB::select(\DB::raw('SELECT text FROM instruksi WHERE pertanyaan_id = '.$value->pertanyaan_id.' '));
        $pilihan[$value->pertanyaan_id]['pertanyaan'] = \DB::select(\DB::raw('SELECT ps.survey_id, p.text, p.nomor, p.jenis_id, ps.pertanyaan_id, ps.sequence, ps.static, ps.position, j.is_multiple  FROM pertanyaan as p 
        JOIN pertanyaan_survey as ps ON ps.pertanyaan_id = p.id
        JOIN jenis as j ON j.id = p.jenis_id
        WHERE ps.survey_id = '.$id.' AND ps.sequence = '.$value->sequence.' AND p.deleted_at IS NULL AND ps.deleted_at IS NULL'));

        if ($value->jenis_id == 5 ) {
        $pil = \DB::select(\DB::raw('SELECT p.id, p.text, p.pertanyaan_id, p.bobot
                    FROM pilihan AS p
                    WHERE p.deleted_at IS NULL and p.pertanyaan_id = '.$value->pertanyaan_id.' '));	
        foreach ($pil as $keyed => $value) {
        $scale = explode('|||',$value->text);
        $bobot = $value->bobot;
        // dd($value);
        }
        $pilihan[$value->pertanyaan_id]['pilihan']['mintext'] = $scale[0];
        $pilihan[$value->pertanyaan_id]['pilihan']['maxtext'] = $scale[1];
        $pilihan[$value->pertanyaan_id]['pilihan']['scale'] = $bobot;

        }else if(($value->jenis_id == 7)){
        // dd($pilihan[$value->pertanyaan_id]['pertanyaan']);
        $pilihan[$value->pertanyaan_id]['pilihan'] = \DB::select(\DB::raw('SELECT pil.id, ps.pertanyaan_id, j.is_multiple, pil.text, pil.bobot  FROM pertanyaan as p 
                    JOIN pertanyaan_survey as ps ON ps.pertanyaan_id = p.id
                    JOIN jenis as j ON j.id = p.jenis_id
                    JOIN pilihan as pil ON pil.pertanyaan_id = ps.pertanyaan_id
                    WHERE ps.survey_id = '.$id.' AND ps.sequence = '.$value->sequence.' AND p.deleted_at IS NULL AND ps.deleted_at IS NULL '));
        }else if(($value->jenis_id == 8)){
        $pilihan[$value->pertanyaan_id]['pilihan'] = \DB::select(\DB::raw('SELECT pil.id, ps.pertanyaan_id, pil.text, pm.is_multiple, pil.bobot  FROM pertanyaan as p 
                    JOIN pertanyaan_survey as ps ON ps.pertanyaan_id = p.id
                    JOIN jenis as j ON j.id = p.jenis_id
                    JOIN pilihan as pil ON pil.pertanyaan_id = ps.pertanyaan_id
                    LEFT JOIN pilihan_multiple as pm ON pm.pertanyaan_id = ps.pertanyaan_id
                    WHERE ps.survey_id = '.$id.' AND ps.sequence = '.$value->sequence.' AND p.deleted_at IS NULL AND ps.deleted_at IS NULL '));
        }else{
        $pilihan[$value->pertanyaan_id]['pilihan'] = \DB::select(\DB::raw('SELECT p.id, p.text, p.pertanyaan_id, p.bobot, p.is_required
                    FROM pilihan AS p
                    WHERE p.deleted_at IS NULL and p.pertanyaan_id = '.$value->pertanyaan_id.' '));

        }

        $pilihan[$value->pertanyaan_id]['rule'] = \DB::select(\DB::raw('SELECT r.id, r.pertanyaan_id, r.pilihan_id, r.next_id, r.scale
                FROM rule as r
                WHERE r.deleted_at IS NULL and r.pertanyaan_id = '.$value->pertanyaan_id.''));
        $pilihan[$value->pertanyaan_id]['anti'] = \DB::select(\DB::raw(' SELECT a.id, a.pertanyaan_id, a.pilihan_id, a.pilihan_id_anti
                FROM anti_pair as a
                WHERE a.deleted_at IS NULL AND a.pertanyaan_id = '.$value->pertanyaan_id.''));
        $urut++;
        $array_key[] =  $value->pertanyaan_id;
        }
        return view('pages.input_soal_web',compact(['pilihan','responden','id','array_key']));
    }
}
