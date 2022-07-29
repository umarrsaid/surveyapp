<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\JawabanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use DB;

class ReportController extends Controller
{
	public function export($id)
    {
        $th1="";
        $th2="";
        $th3="";
        $th4="";
        $arrResp = [];
        $arrJawab = [];
        $arrMultiple = [];
        $soalResps = DB::table('soal_responden')->where('survey_id',$id)->where('deleted_at',null)->whereIN('tipe',['isian','pilihan','tanggal'])->get();
        $soalSurveys = DB::select("SELECT * FROM view_export_soal WHERE survey_id = $id");
        $dataResps = \DB::select("SELECT id
        FROM responden r
        WHERE r.survey_id = $id AND r.deleted_at IS NULL");
        foreach ($soalResps as $key => $soalResp) {
            $isi ="";
            $th1 .= "<th>$soalResp->label</th>";
            foreach($dataResps AS $dataResp){
                $dataJwbResp = \DB::select("SELECT value
                    FROM jawaban_soal_responden 
                    WHERE responden_id = $dataResp->id AND soal_resp_id = $soalResp->id");
                    $arrResp[$dataResp->id][$soalResp->id] = '<td>'.($dataJwbResp != NULL ? $dataJwbResp[0]->value : '').'</td>';
            }
            if ($soalResp->tipe == 'pilihan') {
                foreach (explode(',',$soalResp->value) as $nosoal => $value) {
                    $no = $nosoal+1;
                    $isi .= "$no. $value<br>";
                }
            }
            $th2 .= "<th class='text-left align-middle'>$isi</th>";
        }
        foreach ($soalSurveys as $key => $soalSurvey) {
            if ($soalSurvey->is_multiple == 1) {
                foreach (explode('|=|',$soalSurvey->pilihan) as $key => $pilihan) {
                    $pilihan_id = explode('|=|',$soalSurvey->pilihan_id)[$key];
                    $th3 .= "<th>$soalSurvey->nomor. $pilihan</th>";
                    $th4 .= "<th>0. Not Applied<br>1. Applied</th>";
                    foreach($dataResps AS $dataResp){
                    $dataJwbSoal = \DB::select("SELECT *
                            FROM view_responden_detail 
                            WHERE responden_id = $dataResp->id AND pilihan = $pilihan_id");
                            $arrMultiple[$dataResp->id][$soalSurvey->pertanyaan_id][$pilihan_id] = '<td>'.($dataJwbSoal != null ? 1:0).'</td>';
                    }
                }
            }else{
                $th3 .= "<th>$soalSurvey->nomor. $soalSurvey->text</th>";
                $isi ="";
                if ($soalSurvey->jenis_id == 5) {
                    $x = $soalSurvey->bobot;
                    for ($i=1; $i <= $x; $i++) { 
                        $nilai = $i;
                        if ($i == 1) {
                           $nilai = explode('|||',$soalSurvey->pilihan)[0];
                        } elseif ($i == $x) {
                            $nilai = explode('|||',$soalSurvey->pilihan)[1];
                        }
                        foreach($dataResps AS $dataResp){
                            $dataJwbSoal = \DB::select("SELECT *
                                    FROM view_responden_detail 
                                    WHERE responden_id = $dataResp->id AND pertanyaan_id = $soalSurvey->pertanyaan_id");
                                    $arrJawab[$dataResp->id][$soalSurvey->pertanyaan_id] = '<td>'.($dataJwbSoal != null ? $dataJwbSoal[0]->value:'').'</td>';
                        }
                        $isi .= $i.'. '.$nilai.'<br>';
                    }
                    $th4 .= "<th>$isi</th>";
                } elseif ($soalSurvey->jenis_id == 6) {
                    $th4 .= "<th></th>";
                    foreach($dataResps AS $dataResp){
                        $dataJwbSoal = \DB::select("SELECT *
                                    FROM view_responden_detail 
                                    WHERE responden_id = $dataResp->id AND pertanyaan_id = $soalSurvey->pertanyaan_id");
                                    $arrJawab[$dataResp->id][$soalSurvey->pertanyaan_id] = '<td>'.($dataJwbSoal != null ? $dataJwbSoal[0]->text:'').'</td>';
                        }
                } else {
                    foreach (explode('|=|',$soalSurvey->pilihan) as $nopil => $pilihan) {
                        $pilihan_id = explode('|=|',$soalSurvey->pilihan_id)[$nopil];
                        $no = $nopil+1;
                        $isi .= "$no. $pilihan<br>";
                        foreach($dataResps AS $dataResp){
                            $dataJwbSoal = \DB::select("SELECT *
                                    FROM jawaban 
                                    WHERE responden_id = $dataResp->id AND pertanyaan_id = $soalSurvey->pertanyaan_id");
                            if ($dataJwbSoal != null && $dataJwbSoal[0]->pilihan_id == $pilihan_id) {
                                $arrJawab[$dataResp->id][$soalSurvey->pertanyaan_id] = '<td>'.$no.'</td>';
                            }
                            if (isset($arrJawab[$dataResp->id])) {
                                if (!array_key_exists($soalSurvey->pertanyaan_id,$arrJawab[$dataResp->id]))
                                {
                                    $arrJawab[$dataResp->id][$soalSurvey->pertanyaan_id] = '<td></td>';
                                }
                            }
                        }
                    }
                    $th4 .= "<th>$isi</th>";
                }
            }
        }
        foreach ($arrJawab as $key => $value) {
            if (isset($arrMultiple[$key])) {
                foreach ($arrMultiple[$key] as $key2 => $value2) {
                    $arrJawab[$key][$key2] = implode('',$value2);
                }
            }
        }
        $th1 = "<tr style='background-color:yellow;float:left;'>$th1$th3</tr>";
        $th2 = "<tr style='background-color:yellow;float:left;'>$th2$th4</tr>";
        $body='';
        foreach ($arrResp as $key => $value) {
            $val = implode('',$value);
            ksort($arrJawab[$key]);
            $val2 = implode('',($arrJawab[$key]));
            $body .= "<tr>$val$val2</tr>";
        }
        $th = $th1.$th2; 
        $survey = DB::table('survey')->where('id',$id)->first();
        $data['name'] = 'Data Survey '.$survey->nama.' Per '.date('d-m-Y');
        $data['table'] = "<table>$th$body</table>";
        return view('export.excel',['data' => $data ]);
    }
}
