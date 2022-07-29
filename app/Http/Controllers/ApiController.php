<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function syncDevice(Request $request){

    	$validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        
        try {
        	$nama = $request->nama;
	        $exists = \DB::select(\DB::raw('SELECT id, nama, keterangan, api_token, survey_id FROM device WHERE nama = "'.$nama.'" AND deleted_at IS NULL AND survey_id IS NOT NULL'));

	        if( $exists ) {

		    	$tanggal = \Carbon\Carbon::now('Asia/Jakarta');
	        	$date = date('YmdHis', strtotime($tanggal));
	        	$key = 'Gr1tTeKn0IFF@2019';
	            $usr = base64_encode($nama.$date);
	            $token = hash_hmac('sha256', $usr, $key);

		    	\DB::table('device')->where('nama',$nama)->update([
		    		'api_token' => $token,
		    		'rec_usr' => $exists[0]->id,
		    		'created_at' => $tanggal,
		    	]);

	            return response()->json([
	            	'status' => 200, 
	            	'api_token' => $token,
	            	'device_id' => $exists[0]->id,
	            	'survey_id' => $exists[0]->survey_id,
	            ]);

	        } else {
	            return response()->json(['status' => 404, 'message' => "Data tidak cocok"]);
	        }

	    } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSurvey($id,Request $request){
    	// $id = 1;
    	// $data = \DB::select(\DB::raw('SELECT ps.id, ps.survey_id, ps.pertanyaan_id, ps.sequence, ps.deleted_at, p.text AS pertanyaan, p.jenis_id
					// 				FROM pertanyaan_survey AS ps
					// 				JOIN pertanyaan AS p ON ps.pertanyaan_id = p.id
					// 				WHERE survey_id = 2
					// 				'));
		   //  	foreach ($data as $key => $value) {
		   //  		$pilihan[] = \DB::select(\DB::raw('SELECT pil.id, pil.text AS pilihan,pil.pertanyaan_id, r.pertanyaan_id as id_pertanyaan, per.text as pertanyaan, r.pilihan_id, r.next_id, p.text as pertanyaan_next
					// 	FROM pilihan AS pil
					// 	LEFT JOIN rule AS r ON pil.id = r.pilihan_id
					// 	LEFT JOIN pertanyaan AS per ON per.id = r.pertanyaan_id
					// 	LEFT JOIN pertanyaan AS p ON p.id = r.next_id
					// 	WHERE pil.deleted_at IS NULL AND r.deleted_at IS NULL AND p.deleted_at IS NULL 
					// 	AND pil.pertanyaan_id = '.$value->pertanyaan_id.''));
			   //  	}
    	$datas = \DB::select(\DB::raw('SELECT `sequence`, jenis_id, pertanyaan_id FROM view_api_pertanyaan
                                        WHERE survey_id = '.$id.'
                                        order by sequence ASC
											'));
		$data = [];
		foreach ($datas as $key => $value) {
			$data[$value->sequence] = $value;
		}
    	$urut = 1;
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
    	}
    	// dd($pilihan);
    	if (isset($pilihan)) {
    		return [$id => $pilihan];
    	}else{
    		return response()->json(['message' => 'Data tidak ada'],400);
    	}
    	// return 'tes';
    }
	
    public function postSurvey($id = NULL,Request $request){

		$input = $request->all();
		\DB::transaction(function () use ($input) {
			date_default_timezone_set('Asia/Jakarta');
			foreach ($input as $key => $value) {
				$survey_id = $value['responden']['survey_id'];
				$responden = \DB::table('responden')->insertGetId([
					'survey_id' => $survey_id,
					'waktu_mulai' => date("Y-m-d H:i:s",round($value['responden']['waktu_mulai']/1000)),
					'waktu_selesai' => date("Y-m-d H:i:s",round($value['responden']['waktu_selesai']/1000)),
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
				]);
				foreach ($value['detail'] as $key3 => $val2) {
					$values = $val2['value'];
					$type = $val2['tipe'];
					
					if ($type == 'foto' && $values != "") {
						$id = $val2['soalresp_id'];
						$nama = \DB::select("SELECT label FROM soal_responden where id = $id ")[0]->label.'_'.$survey_id.'_'.$responden;
						$img = (imagecreatefromstring(base64_decode($values)));
						header('Content-Type: image/jpeg');
						imagejpeg($img,public_path("file-image/").$nama.'.jpeg');            
						imagedestroy($img);
						$values = $nama.'.jpeg';
					}

					if($values != ""){
						\DB::table('jawaban_soal_responden')->insert([
							'survey_id' => $survey_id,
							'soal_resp_id' => $val2['soalresp_id'],
							'responden_id' => $responden,
							'value' => $values,
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now(),
						]);
					}
				}
				foreach ($value['jawaban'] as $key2 => $val) {
					if(isset($val['pilihan_id'])&& $val['pilihan_id'] != null) {
						\DB::table('jawaban')->insert([
							'pertanyaan_id' => $val['pertanyaan_id'],
							'pilihan_id' => $val['pilihan_id'],
							'responden_id' => $responden,
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now(),
						]);
					}elseif(isset($val['isian'])&& $val['isian'] != null){
						\DB::table('isian')->insert([
							'pertanyaan_id' => $val['pertanyaan_id'],
							'text' => $val['isian'],
							'responden_id' => $responden,
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now(),
						]);
					}elseif(isset($val['scale'])&& $val['scale'] != null){
						\DB::table('pertanyaan_scale')->insert([
							'pertanyaan_id' => $val['pertanyaan_id'],
							'value' => $val['scale'],
							'responden_id' => $responden,
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now(),
						]);
					}
				}
			}
		});
		return response()->json(["message" => 'success'], 200);

	}

    public function cekImei(Request $request){

    	$validator = Validator::make($request->all(), [
            'username' => 'required',
            'imei' => 'required',
        ],[
            'username.required' => 'Nama User wajib di isi'
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        
        try {

	    	$imei = $request->imei;
	    	$username = $request->username;

	    	$cek_imei = \DB::select(\DB::raw('SELECT d.id, d.nama, d.survey_id FROM device as d WHERE d.imei = "'.$imei.'" AND d.deleted_at IS NULL LIMIT 1'));
	    	$cek_interviewer = \DB::select(\DB::raw('SELECT i.id, i.nama, m.survey_id FROM interviewer as i 
			LEFT JOIN multisurvey m ON m.interviewer_id = i.id
			WHERE i.nama = "'.$username.'" AND i.deleted_at IS NULL AND m.deleted_at IS NULL LIMIT 1'));

	    	if ( (!empty($cek_imei)) && (!empty($cek_interviewer)) ) {

	    		if ($cek_interviewer[0]->survey_id == null) {
		    		return response()->json(['message' => 'Survey belum diseting pada device.'],400);
	    		}else{

	    			$tanggal = \Carbon\Carbon::now('Asia/Jakarta');
		        	$date = date('YmdHis', strtotime($tanggal));
		        	$key = 'Gr1tTeKn0IFF@2019';
		            $usr = base64_encode($imei.$date);
		            $token = hash_hmac('sha256', $usr, $key);

			    	\DB::table('device')->where('imei',$imei)->update([
			    		'api_token' => $token,
			    		'created_at' => $tanggal,
					]);
					$interviewer_id = $cek_interviewer[0]->id;
					$multi = \DB::select("SELECT * FROM multisurvey a LEFT JOIN survey b ON b.id = a.survey_id WHERE interviewer_id = $interviewer_id AND b.deleted_at IS NULL");

					if ($multi != NULL) {
						foreach ($multi as $key => $value) {
							$is_reusable = \DB::select('SELECT is_reusable FROM survey WHERE id = '.$value->survey_id.'')[0]->is_reusable;
							if ($is_reusable == 2) {
								$data['klasifikasi'][$value->survey_id] = \DB::select("SELECT klasifikasi, logika, nilai_a, nilai_b FROM klasifikasi_skor Where survey_id = $value->survey_id AND deleted_at IS NULL");
							}
							$arraySurveyId[] = $value->survey_id;
							$arrayNama[] = $value->nama;
							$arrayResponden[$value->survey_id] = \DB::select("SELECT id, tipe, is_required, label, value FROM soal_responden Where survey_id = $value->survey_id AND deleted_at IS NULL");
						}
					}
		    		$data['survey_id'] = $arraySurveyId;
                    $data['soal_responden'] = $arrayResponden;
                    $data['nama_survey'] = $arrayNama;
                    $data['durasi'] = 0;
                    $data['api_token'] = $token;
		    		$data['status_code'] = 200;
		    		return $data;
	    		}

	    	}else{
	    		return response()->json(['message' => 'Data tidak ada'],400);
	    	}
    	} catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
