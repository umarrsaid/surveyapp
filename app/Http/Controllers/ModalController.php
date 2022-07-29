<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;

class ModalController extends Controller
{

    public function soalResp($id = NULL, Request $request){
        $ids = Crypt::decryptString($id);
        $klas_skor = DB::table('soal_responden')->where('survey_id',$ids)->where('deleted_at',NULL)->get();
        $klasifikasi = '
                    <div class="det-row">
                        <div class="row">
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-danger delete-det">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="tipe[]" class="form-control" required>
                                        <option value="isian">Isian</option>
                                        <option value="pilihan">Pilihan</option>
                                        <option value="tanggal">Tanggal</option>
                                        <option value="foto">Foto</option>
                                        <option value="lokasi">Lokasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <input class="form-control" value="" name="label[]" required>
                                <input type="hidden" class="form-control" value="0" name="is_required[]" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <textarea class="form-control" name="value[]" readonly style="resize:none;">-</textarea>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                    <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" class="check"> Required
                                    <span></span>
                            </label>
                            </div>
                        </div>
                    </div>
                        '
                    ;
            if (count($klas_skor) != 0) {
                $klasifikasi = '';
                foreach ($klas_skor as $key => $value) {
                    $klasifikasi .= '
                    <div class="det-row">
                        <input class="form-control input-sm" type="hidden" name="id_soal_resp[]" value="'.$value->id.'">
                        <div class="row">
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-danger delete-det">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="tipe[]" class="form-control" required>
                                        <option value="isian" '.($value->tipe =='isian' ? 'selected':'').'>Isian</option>
                                        <option value="pilihan" '.($value->tipe =='pilihan' ? 'selected':'').'>Pilihan</option>
                                        <option value="tanggal" '.($value->tipe =='tanggal' ? 'selected':'').'>Tanggal</option>
                                        <option value="foto" '.($value->tipe =='foto' ? 'selected':'').'>Foto</option>
                                        <option value="lokasi" '.($value->tipe =='lokasi' ? 'selected':'').'>Lokasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <input class="form-control" value="'.$value->label.'" name="label[]" required>
                                <input type="hidden" class="form-control" value="'.$value->is_required.'" name="is_required[]" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <textarea class="form-control" name="value[]" '.($value->tipe =='pilihan' ? '':'readonly').' style="resize:none;">'.($value->tipe =='pilihan' ? $value->value:'-').'</textarea>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" class="check" '.($value->is_required == 1 ? 'checked':'').'> Required
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                            '
                        ;
                }
            }
        $modal_size = 'modal-md';
        $modal_header = '<i class="fa fa-pencil" aria-hidden="true"></i> <span> Input </span> Soal reponden';
        $modal_body = '
            <div class="row" style="padding-bottom:10px;">
            <input name="ref" value="'.$id.'" type="hidden"> 
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-success add-det">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <div class="col-sm-2">
                    Tipe
                </div>
                <div class="col-sm-3">
                    Label
                </div>
                <div class="col-sm-4">
                    Value
                </div>
                <div class="col-sm-2">
                    
                </div>
            </div>
                '. $klasifikasi .'
            <div class="row">
                <div class="col-sm-12"><span style="width: 85%;margin-top: .25rem;font-size: 90%;color: red; float:left"><sup>*</sup><b>Value</b> diisi jika tipe adalah <b><i>pilihan</i></b> dan dipisahkan dengan <b>tanda koma (,)</b></span>
                </div>
            </div>

        ';

        $modal_footer = '
        <button type="submit" class="btn btn-primary" id="btn-save" value="soal-resp"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function klasifikasiSkor($id = NULL, Request $request){
        $ids = Crypt::decryptString($id);
        $klas_skor = DB::table('klasifikasi_skor')->where('survey_id',$ids)->where('deleted_at',NULL)->get();
        $klasifikasi = '
                        <div class="det-row">
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-danger delete-det">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div><div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control input-sm" name="klasifikasi[]" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="logika[]" class="form-control input-sm" required>
                                        <option value=">=">>=</option>
                                        <option value=">">></option>
                                        <option value="<"><</option>
                                        <option value="<="><=</option>
                                        <option value="range">Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input class="form-control input-sm number" value="0" name="nilai_a[]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input class="form-control input-sm number" value="0" name="nilai_b[]">
                                </div>
                            </div>
                        </div>
                        '
                    ;
        if (count($klas_skor) != 0) {
            $klasifikasi = '';
            foreach ($klas_skor as $key => $value) {
                $klasifikasi .= '
                    <div class="det-row">
                        <div class="col-md-1">
                        <input class="form-control input-sm" type="hidden" name="id_klas[]" value="'.$value->id.'">
                            <button type="button" class="btn btn-sm btn-danger delete-det">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div><div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control input-sm" name="klasifikasi[]" value="'.$value->klasifikasi.'" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="logika[]" class="form-control input-sm" required>
                                    <option '.($value->logika == '>=' ? 'selected' :'').' value=">=">>=</option>
                                    <option '.($value->logika == '>' ? 'selected' :'').' value=">">></option>
                                    <option '.($value->logika == '<' ? 'selected' :'').' value="<"><</option>
                                    <option '.($value->logika == '<=' ? 'selected' :'').' value="<="><=</option>
                                    <option '.($value->logika == 'range' ? 'selected' :'').' value="range">Range</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input class="form-control input-sm number" value="'.$value->nilai_a.'" name="nilai_a[]" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input class="form-control input-sm number" value="'.$value->nilai_b.'" name="nilai_b[]">
                            </div>
                        </div>
                    </div>
                        '
                    ;
            }
        }
        $modal_size = 'modal-mk';
        $modal_header = '<i class="fa fa-pencil" aria-hidden="true"></i> <span> Input </span> Klasifikasi Skor';
        $modal_body = '
            <div class="row">
            <input name="ref" value="'.$id.'" type="hidden"> 
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-success add-det">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <div class="col-sm-4 text-center">
                    Klasifikasi
                </div>
                <div class="col-sm-3 text-center">
                    Logika
                </div>
                <div class="col-sm-2 text-center">
                    Nilai Pertama
                </div>
                <div class="col-sm-2 text-center">
                    Nilai Kedua
                </div>
            </div>
            <div class="row">
                '. $klasifikasi .'
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span style="width: 85%;margin-top: .25rem;font-size: 90%;color: red; float:left"><sup>*</sup><b>Nilai kedua</b> harus diisi jika tipe logika adalah <b><i>range</i></b></span>
                </div>
            </div>

        ';

        $modal_footer = '
        <button type="submit" class="btn btn-primary" id="btn-save" value="klasifikasi"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function linkedSurvey($id = NULL, Request $request){
        $ids = Crypt::decryptString($id);
        $survey = DB::table('survey')->where('soal_resp',1)->where('deleted_at',NULL)->get();
        $multisurvey = DB::table('multisurvey')->select('survey_id')->where('interviewer_id',$ids)->where('deleted_at',NULL)->get();
        $array_id = array();
        if ($multisurvey != NULL) {
            foreach ($multisurvey as $key => $value) {
                $array_id[] = $value->survey_id;
            }
        }
        $tr = '';
        foreach ($survey as $key => $value) {
            if ($value->is_reusable == 2 && $value->skor == 0 && $value->klasifikasi == 0) {
                continue;
            }
            $tr .= '<tr>
                <td class="text-center">
                    <div class="c-checkbox">
                    <label>
                        <input type="checkbox" value="'.$value->id.'" '.(in_array($value->id,$array_id) == true ? 'checked':'').' name="surveyreusable[]">
                        <span class="cr" style="margin-right: 0px;"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                    </label>
                    </div>
                </td>
                <td>'.$value->nama.'</td>
            </tr>';
        }
        $modal_size = 'modal-full';
        $modal_header = '<i class="fa fa-chain" aria-hidden="true"></i> <span> Lampirkan </span> Survey';
        $modal_body = '
            <div class="row">
                <input name="ref" value="'.$id.'" type="hidden">
                <div class="col-md-12">
                    <table id="tblmulti" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center"></th>
                                <th>Nama Survey</th>
                            </tr>
                        </thead>
                        <tbody>
                            '.$tr.'
                        </tbody>
                    </table>
                </div>
            </div>

        ';

        $modal_footer = '
        <button type="submit" class="btn btn-primary" id="btn-save" value="linked"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function addModalSurvey($ref = NULL, Request $request){
        $modal_size = 'modal-mk';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i> <span> Tambah </span> Project';
        $reusable = '';
        if ($ref != NULL) {
            $reusable = '<input type="hidden" name="reusable" value="1">';
        }
        $modal_body = '
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Nama </label>
                            '.$reusable.'
                            <input type="text" class="form-control" placeholder="Nama" name="nama">
                            <span class="help-block has-error jps_error"></span>    
                            <input type="hidden" class="form-control" id="durasi" maxlength="5" value="0" placeholder="Durasi" name="durasi">
                        </div>
                    </div>
                </div>
            </div>
        ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="tambah"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function editModalSurvey($ids,Request $request){
       $id = Crypt::decryptString($ids);
       $modal_size = 'modal-mk';

       $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i> <span> Edit </span> Survey';

       $data = \DB::select(\DB::raw('SELECT id, nama, durasi FROM survey WHERE deleted_at IS NULL AND id = '.$id.' LIMIT 1 '));

       $modal_body = '
           <div class="row">
               <div class="col-md-12">
                       <div class="form-group">
                           <label> Nama </label>
                           <input type="text" class="form-control" placeholder="Nama" name="nama" value="'.$data[0]->nama.'">
                           <span class="help-block has-error jps_error"></span>    
                           <input type="hidden" class="form-control" id="durasi" maxlength="5" placeholder="Durasi" value="'.$data[0]->durasi.'" name="durasi">
                       </div>
                   </div>
               </div>
           </div>
       ';

       $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" data-id="'.$data[0]->id.'" value="edit"> <i class="fa fa-check"></i> Simpan </button>
       <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

       $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
       return $data;
   }

   public function copyModalSurvey($ids,Request $request){
      $id = Crypt::decryptString($ids);
      $modal_size = 'modal-mk';

      $modal_header = '<i class="fa fa-copy" aria-hidden="true"></i> <span> Copy </span> Project';

      $data = \DB::select(\DB::raw('SELECT id, nama, durasi FROM survey WHERE deleted_at IS NULL AND id = '.$id.' LIMIT 1 '));

      $modal_body = '
          <div class="row">
              <div class="col-md-12">
                      <div class="form-group">
                          <label> Nama </label>
                          <input type="text" class="form-control" placeholder="Nama" name="nama" value="'.$data[0]->nama.'">
                          <span class="help-block has-error jps_error"></span>    
                          <input type="hidden" class="form-control" id="durasi" maxlength="5" placeholder="Durasi" value="'.$data[0]->durasi.'" name="durasi">
                      </div>
                  </div>
              </div>
          </div>
          

      ';

      $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" data-id="'.$data[0]->id.'" value="copy"> <i class="fa fa-check"></i> Simpan </button>
      <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

      $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
      return $data;
  }

     public function addModalDevice(Request $request){
        $modal_size = 'modal-mk';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i> <span> Tambah </span> Devices';
        $data = \DB::select(\DB::raw('SELECT id, nama FROM survey WHERE is_reusable = "0" AND deleted_at IS NULL '));
        foreach ($data as $key => $value) {
            $form_option[] = '<option value="'.$value->id.'""> '.$value->nama.' </option>';                          
        }

        $modal_body = '
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Nama </label>
                            <input type="text" class="form-control" placeholder="Nama" name="nama" required>
                            <span class="help-block has-error"></span>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Imei Device </label>
                            <input type="text" class="form-control" placeholder="Imei Device" name="imei" required>
                            <span class="help-block has-error"></span>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Keterangan </label>
                            <input type="text" class="form-control" id="keterangan" maxlength="5" placeholder="Keterangan" name="keterangan" required>
                            <span class="help-block has-error"></span>    
                        </div>
                    </div>
                </div>
            </div>

           ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="tambah"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

        public function editModalDevice($id,Request $request){
        $modal_size = 'modal-mk';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i> <span> Edit </span> Devices';

        $data = \DB::select(\DB::raw('SELECT id, nama, imei, keterangan, survey_id FROM device WHERE deleted_at IS NULL AND id = '.$id.' LIMIT 1 '));

        $survey = \DB::select(\DB::raw('SELECT id, nama FROM survey WHERE is_reusable = "0" AND deleted_at IS NULL '));

        foreach ($survey as $key => $value) {
            if ($data[0]->survey_id == $value->id) {
                $form_option[] = '<option selected value="'.$value->id.'""> '.$value->nama.' </option>';                          
            }else{
                $form_option[] = '<option value="'.$value->id.'""> '.$value->nama.' </option>';                          
            }
        }

        // foreach (\Iff::getAllSurvey() as $key => $value) {
        //     $form_option[] = '<option value="'.$value->id.'""> '.$value->nama.' </option>';                           
        // }
        // return $data;
        $modal_body = '
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Nama </label>
                            <input type="text" class="form-control" placeholder="Nama" name="nama" value="'.$data[0]->nama.'" required>
                            <span class="help-block has-error jps_error"></span>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Imei Device </label>
                            <input type="text" class="form-control" placeholder="Imei Device" name="imei" value="'.$data[0]->imei.'" required>
                            <span class="help-block has-error"></span>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Keterangan </label>
                            <input type="text" class="form-control" id="keterangan" maxlength="5" placeholder="Keterangan" name="keterangan" value="'.$data[0]->keterangan.'" required>
                            <span class="help-block has-error jps_error"></span>    
                        </div>
                    </div>
                </div>
            </div>

           ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" data-id="'.$data[0]->id.'" value="edit"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }


    public function pertanyaan($jenis,$no,Request $request){
    	$i = 0;
    	switch ($jenis) {
    		case '1':
    			$form = ' <div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="1">  
                                     Dichotomous
                                    
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" id="btn-rule1" class="btn-rule1"  data-jenis="1" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" id="nomer_urut" value="'.$no.'">
                                        <div id="input_rule'.$no.'" class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Static Content</label>
                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'">
                                                </textarea>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Posisi Static</label>
                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                            </div>
                                            <div class="form-group col-md-9">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" id="pertanyaan_'.$no.'" maxlength="500" required></textarea>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <textarea class="form-control input-sm pilihan_'.$no.'" id="pilihan_'.$no.'" name="pilihan['.$no.'][]" placeholder="" readonly>Ya</textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <textarea class="form-control input-sm pilihan_'.$no.'" id="pilihan_'.$no.'" name="pilihan['.$no.'][]" placeholder="" readonly>Tidak</textarea>
                                            </div>
                                        </div>

                                        <div id="input2-rule'.$no.'" class="col-md-6">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>';
                        $i+1;
                        return $form;
    			break;
    		case '2':
    			$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="2">  


                                     Multiple Choices, Single Answer

                                     <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" id="btn-rule1" class="btn-rule1"  data-jenis="1" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                    	<input type="hidden" id="nomer_urut" value="'.$no.'">
                                        <div id="input_rule'.$no.'" class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Static Content</label>
                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'">
                                                </textarea>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Posisi Static</label>
                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                            </div>

                                            <div class="form-group col-md-9">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan['.$no.'][]" id="pertanyaan_'.$no.'" required></textarea>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <div id="line-pilihan'.$no.'_1">
                                                    <div class="input-group">
                                                        <textarea class="form-control input-sm keyup-pilihan2 pilihan_'.$no.' disable_pilihan'.$no.'" name="pilihan['.$no.'][]"  placeholder="Pilihan" required></textarea>
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-no="'.$no.'"  class="fa fa-trash btn_pilihan'.$no.'" id="line-pilihan" style="color:red;"> Delete</a> </span>
                                                        <span class="input-group-addon" ><input type="radio" name="is_req_pilihan['.$no.'][]" value="1"> Require</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <div id="pilihan_'.$no.'"></div>
                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan'.$no.'" data-no-pilihan="'.$no.'" id="btn_tambah_pilihan2"><i class="fa fa-plus"></i> Tambah Pilihan</button>
                                            </div>

                                        </div>
                                        <div id="input2-rule'.$no.'" class="col-md-6">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                return $form;
    		break;
    		case '3':
    			$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="3">  

                                     Multiple Choices, Multiple Answer

                                     <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" id="btn-anti'.$no.'" class="btn-anti3" data-jenis="2" data-sequence="'.$no.'" data-count="1" data-anti="uncek"> Rule Anti</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" id="nomer_urut" value="'.$no.'">
                                        <div id="input_rule'.$no.'" class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Static Content</label>
                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'">
                                                </textarea>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Posisi Static</label>
                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                            </div>
                                            
                                            <div class="form-group col-md-9">
                                                <label>Pertanyaan</label>
                                                <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" maxlength="500" required></textarea>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <div id="line-pilihan'.$no.'_1">
                                                    <div class="input-group">
                                                    <textarea type="text" class="form-control input-sm keyup-pilihan3 pilihan_'.$no.' disable_pilihan'.$no.'" name="pilihan['.$no.'][1]"  placeholder="Pilihan" required></textarea>
                                                    <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-no="'.$no.'"  class="fa fa-trash btn_pilihan'.$no.'" id="line-pilihan" style="color:red;"> Delete</a> </span>
                                                    <span class="input-group-addon" ><input type="checkbox" name="is_req_pilihan['.$no.'][1]" value="1"> Require</span>
                                                    </div>
                                            </div>
                                        
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <div id="pilihan_'.$no.'"></div>
                                            <button type="button" data-case="1" class="btn blue disable_pilihan'.$no.'" data-no-pilihan="'.$no.'" id="btn_tambah_pilihan3"><i class="fa fa-plus"></i> Tambah Pilihan</button>
                                        </div>
                                    
                                        </div>
                                        
                                        <div id="input2-rule'.$no.'" class="col-md-6">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                return $form;
    		break;
            case '4':
                $form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                         <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                         <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="4">  

                                         Rating Scale

                                         <div class="pull-right">
                                            <div class="tools">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a href="javascript:;" id="btn-rule1" class="btn-rule1"  data-jenis="1" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                       <div class="pull-right">
                                            <div class="tools">
                                                <div class="btn-group">
                                                    <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                        <span><i class="fa fa-trash red"></i> Delete </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        <div class="row">
                                            <input type="hidden" id="nomer_urut" value="'.$no.'">
                                            <div id="input_rule'.$no.'" class="col-md-12">

                                                <div class="form-group col-md-12">
                                                    <label>Static Content</label>
                                                    <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'">
                                                    </textarea>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label>Posisi Static</label>
                                                    <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                        <option>Atas</option>
                                                        <option>Bawah</option>
                                                    </select>
                                                    <br>
                                                    <label>Nomor</label>
                                                    <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                                </div>

                                                <div class="form-group col-md-9">
                                                    <label>Pertanyaan</label>
                                                    <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" maxlength="500" required></textarea>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label>Scale Text</label>
                                                    <div id="line-pilihan'.$no.'_1">
                                                        <div class="input-group">
                                                        <textarea class="form-control input-sm keyup-pilihan4 pilihan_'.$no.' disable_pilihan'.$no.'" name="pilihan['.$no.'][]"  placeholder="Scale Text" maxlength="185"></textarea>
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-no="'.$no.'"  class="fa fa-trash btn_pilihan'.$no.'" id="line-pilihan" style="color:red;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>

                                                <div class="form-group col-md-12">
                                                    <div id="pilihan_'.$no.'"></div>
                                                    <button type="button" data-case="1" class="btn blue btn_pilihan'.$no.'" data-no-pilihan="'.$no.'" id="btn_tambah_scale4"><i class="fa fa-plus"></i> Tambah Pilihan</button>
                                                </div>

                                            
                                            </div>
                                            <div id="input2-rule'.$no.'" class="col-md-6">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>';
                return $form;
            break;
            case '5':
                $form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="5">  

                                     Semantic Differential

                                     <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" class="btn-rule-semantic"  data-jenis="3" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-body">
                                        <div class="row">
                                            <input type="hidden" id="nomer_urut" value="'.$no.'">
                                            <div id="input_rule'.$no.'" class="col-md-12">
                                                
                                                <div class="form-group col-md-12">
                                                    <label>Static Content</label>
                                                    <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'">
                                                    </textarea>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label>Posisi Static</label>
                                                    <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                        <option>Atas</option>
                                                        <option>Bawah</option>
                                                    </select>
                                                    <br>
                                                    <label>Nomor</label>
                                                    <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                                </div>

                                                <div class="form-group col-md-9">
                                                    <label>Pertanyaan</label>
                                                    <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" maxlength="500" required></textarea>
                                                </div>
                                            
                                                <div class="col-md-5">
                                                    <label> Minimal Text </label>
                                                    <input type="text" class="form-control input-sm disable_pilihan'.$no.'" name="pilihan['.$no.'][]" maxlength="75"  required placeholder="Min. Text"> 
                                                </div>
                                                <div class="col-md-5">
                                                    <label> Maximal Text </label>
                                                    <input type="text" class="form-control input-sm disable_pilihan'.$no.'" name="pilihan['.$no.'][]" maxlength="75" required placeholder="Max. Text"> 
                                                </div>
                                                <div class="col-md-2">
                                                    <label> Scale </label>
                                                    <input type="text" class="form-control number input-sm disable_pilihan'.$no.'" id="scale'.$no.'" maxlength="2" name="scale['.$no.'][]"  required placeholder="Scale"> 
                                                </div>
                                            </div>
                                            
                                            <div id="input2-rule'.$no.'" class="col-md-6">
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>';
                return $form;
            break;
            case '6':
                $form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="6">  

                                     Open Ended

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" id="nomer_urut" value="'.$no.'">
                                        <div id="input_rule'.$no.'" class="col-md-12">
                                            
                                            <div class="form-group col-md-12">
                                                <label>Static Content</label>
                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'">
                                                </textarea>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Posisi Static</label>
                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                            </div>
                                            
                                            <div class="form-group col-md-9">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" rows="5" maxlength="500" name="pertanyaan['.$no.'][]" required></textarea>
                                            </div>
                                        </div>
                                    
                                        <div id="input2-rule'.$no.'" class="col-md-6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                return $form;
            break;
            case '7':
                $form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="7">  

                                     Matrix Table

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" id="nomer_urut" value="'.$no.'">
                                        <div id="input_rule'.$no.'" class="col-md-12">
                                
                                            <div class="form-group col-md-12">
                                                <label>Static Content</label>
                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'"></textarea>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Posisi Static</label>
                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                            </div>

                                            <div class="form-group col-md-9">
                                                <label>Instruksi</label>
                                                <textarea class="form-control input-sm" name="instruksi['.$no.']" maxlength="500" rows="5" required></textarea> 
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Scale</label>
                                                <div id="line-pilihan'.$no.'_1">
                                                    <div class="input-group">

                                                        <input type="text" class="form-control input-sm pilihan_'.$no.'" name="pilihan['.$no.'][]" maxlength="185" placeholder="Scale Text" required> 
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-no="'.$no.'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="form-group col-md-12">
                                                <div id="pilihan_'.$no.'"></div>
                                                <button type="button" data-counter-scale="1" data-no-scale="'.$no.'" class="btn blue btn-sm" id="btn_tambah_scale7"><i class="fa fa-plus"></i> Tambah Scale</button>
                                            </div>


                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                    <div id="line-pertanyaan'.$no.'_1">
                                                        <div class="input-group">

                                                        <input type="text" class="form-control input-sm pertanyaan_'.$no.'" name="pertanyaan['.$no.'][]"  placeholder="Pertanyaan" maxlength="500" required> 
                                                    <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-no="'.$no.'" id="line-pertanyaan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group col-md-12">
                                                <div id="pertanyaan_'.$no.'"></div>
                                                <button type="button" data-counter-pertanyaan="1" data-no-pertanyaan="'.$no.'" data-case="7" class="btn-sm btn blue" id="btn_tambah_pertanyaan7"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
                                            </div>
                                        </div>
                                        <div id="input2-rule'.$no.'" class="col-md-6">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                return $form;
            break;
            case '8':
                $form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="8">  


                                     Side-Beside Matrix

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
                                    <div id="input_rule'.$no.'">
                                        <div class="form-group col-md-12">
                                            <label>Static Content</label>
                                            <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'"></textarea>
                                            <br>
                                            <label>Posisi Static</label>
                                            <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
                                        </div>
                                        
                                        <div id="line-instruksi'.$no.'_1" class="instruksi'.$no.'">
                                            <div class="form-group col-md-2">
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                                <br>
                                                <label>Type</label>
                                                <select class="form-control input-sm" name="tipe_pilihan['.$no.'][]" required>
                                                    <option value="0">Single Choice</option>
                                                    <option value="1">Multiple Choice</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-10" >
                                                <label>Instruksi / Pertanyaan</label>
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" maxlength="500" placeholder="Tulis Instruksi" required></textarea>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <a href="javascript:;" data-counter="1" data-no="'.$no.'" id="line-instruksi" class="btn btn-danger btn-circle" ><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="instruksi_'.$no.'">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <button type="button" data-counter-instruksi="1" data-no-instruksi="'.$no.'" class="btn blue" id="btn_tambah_instruksi"><i class="fa fa-plus"></i> Tambah Instruksi</button>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Pilihan</label>
                                            <div class="form-group" id="line-pilihan'.$no.'_1">
                                                <div class="input-group">

                                                    <input type="text" class="form-control input-sm pilihan_'.$no.'" name="pilihan['.$no.'][]" maxlength="185" placeholder="Pilihan" required> 
                                                <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-no="'.$no.'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="pilihan_'.$no.'"></div>
                                        <div class="form-group col-md-12">
                                            <button type="button" data-counter-pilihan="1" data-no-pilihan="'.$no.'" data-case="8" class="btn blue" id="btn_tambah_pilihan8"><i class="fa fa-plus"></i> Tambah pilihan</button>
                                        </div>

                                    </div>
                                    <div id="input2-rule'.$no.'" class="col-md-6">
                                    </div>
                                </div>
                            </div>
                        </div>';
                return $form;
            break;
            case '9':
                $form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="9">   

                                     Biodata Responden
                                    
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
                                    <div id="input_rule'.$no.'">
                                        <div class="form-group col-md-12">
                                            <label>Static Content</label>
                                            <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'"></textarea>
                                            <br>
                                            <label>Posisi Static</label>
                                            <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
                                        </div>

                                        <input type="hidden" id="nomer_urut" value="">
                                        
                                            <div class="col-md-12">
                                                  <label>Pertanyaan</label>
                                                <div class="row">
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$no.'][]" id="checkbox5" value="Nomor Responden" checked/>
                                                          <label for="checkbox5">Nomor Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$no.'][]" id="checkbox1" value="Nama Responden" checked/>
                                                          <label for="checkbox1">Nama Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$no.'][]" id="checkbox2" value="Telepon Responden" checked/>
                                                          <label for="checkbox2">Telepon Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$no.'][]" id="checkbox3" value="Umur Responden" checked/>
                                                          <label for="checkbox3">Umur Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$no.'][]" id="checkbox4" value="Email Responden" checked/>
                                                          <label for="checkbox4">Email Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                
                                                </div>
                                            </div>
                                            
                                        </div>
                                </div>
                                </div>
                                </div>';
                return $form;
            break;
    		default:
    			# code...
    			break;
    	}
    }

    public function pilihan($jenis,Request $request){

    }

    public function addModalReusable(Request $request){
        $modal_size = 'modal-mk';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i> <span> Tambah </span> Pertanyaan';

        $modal_body = '
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Nama Template</label>
                            <input type="text" class="form-control" placeholder="Nama" name="nama" required>
                            <span class="help-block has-error jps_error"></span>    
                        </div>
                    </div>
                </div>
            </div>
        ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="tambah"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function editModalReusable($ids,Request $request){
        $id = Crypt::decryptString($ids);

        $modal_size = 'modal-mk';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i> <span> Edit </span> Survey';

        $data = \DB::select(\DB::raw('SELECT id, nama FROM survey WHERE deleted_at IS NULL AND id = '.$id.' LIMIT 1 '));

        $modal_body = '
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label> Nama </label>
                            <input type="text" class="form-control" placeholder="Nama" name="nama" value="'.$data[0]->nama.'" required>
                            <span class="help-block has-error jps_error"></span>    
                        </div>
                    </div>
                </div>
            </div>
        ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" data-id="'.$data[0]->id.'" value="edit"> <i class="fa fa-check"></i> Simpan </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top: 0px;"> <i class="fa fa-close"></i> Batal </button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function formReusable($jenis, Request $request)
    {
        $jenis = $request->jenis;

        switch ($jenis) {
            case '1':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">
                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" rows="5" name="pertanyaan" id="pertanyaan" maxlength="500" required></textarea>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <input type="text" class="form-control input-sm" id="pilihan" name="pilihan[]" placeholder="" value="Ya" readonly> 
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control input-sm" id="pilihan" name="pilihan[]" placeholder="" value="Tidak" readonly> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '2':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required></textarea>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <div id="line-pilihan">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" required> 
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" class="fa fa-trash" id="delete_pilihan0" style="color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="pilihan"></div>

                                            <div class="form-group col-md-12">
                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan" id="btn_tambah_pilihan"><i class="fa fa-plus"></i> Tambah Pilihan</button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '3':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required></textarea>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <div id="line-pilihan">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" required> 
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" class="fa fa-trash" id="delete_pilihan0" style="color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="pilihan"></div>

                                            <div class="form-group col-md-12">
                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan" id="btn_tambah_pilihan"><i class="fa fa-plus"></i> Tambah Pilihan</button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '4':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required></textarea>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Scale Text</label>
                                                <div id="line-pilihan">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Scale Text" maxlength="185" required> 
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" class="fa fa-trash" id="delete_pilihan0" style="color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="pilihan"></div>

                                            <div class="form-group col-md-12">
                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-scale" id="btn_tambah_scale"><i class="fa fa-plus"></i> Tambah Scale</button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '5':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required></textarea>
                                            </div>
                                            
                                            <div class="col-md-5">
                                                <label> Minimal Text </label>
                                                <input type="text" class="form-control input-sm disable_pilihan" name="pilihan[]" maxlength="75"  required placeholder="Min. Text"> 
                                            </div>
                                            <div class="col-md-5">
                                                <label> Maximal Text </label>
                                                <input type="text" class="form-control input-sm disable_pilihan" name="pilihan[]" maxlength="75" required placeholder="Max. Text"> 
                                            </div>
                                            <div class="col-md-2">
                                                <label> Scale </label>
                                                <input type="text" class="form-control number input-sm disable_pilihan" id="scale" maxlength="2" name="scale"  required placeholder="Scale"> 
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '6':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required></textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '7':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div class="form-group col-md-12">
                                                <label>Instruksi</label>
                                                <textarea class="form-control input-sm" name="instruksi" maxlength="500" rows="5" required></textarea> 
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <div id="line-pilihan">
                                                    <label>Scale</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm" name="pilihan[]" maxlength="185" placeholder="Scale Text" required> 
                                                        <span class="input-group-addon" ><a href="javascript:;" class="fa fa-trash" id="delete_pilihan0" style="color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="pilihan"></div>

                                            <div class="form-group col-md-12">
                                                <button type="button" class="btn blue btn-sm" id="btn_tambah_scale"><i class="fa fa-plus"></i> Tambah Scale</button>
                                            </div>


                                            <div class="form-group col-md-12">
                                                <label>Pertanyaan</label>
                                                    <div id="line-pertanyaan">
                                                        <div class="input-group">

                                                        <input type="text" class="form-control input-sm" name="pertanyaan[]"  placeholder="Pertanyaan" maxlength="500" required> 
                                                    <span class="input-group-addon" ><a href="javascript:;" class="fa fa-trash" id="delete_pertanyaan0" style="color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;"> Delete</a> </span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div id="pertanyaan_mt"></div>

                                            <div class="form-group col-md-12">
                                                <button type="button" data-case="7" class="btn-sm btn blue" id="btn_tambah_pertanyaan"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            case '8':
                $form = '<div class="portlet-body form connectedSortable reusableForms">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
                                        <div class="col-md-12">

                                            <div id="line-instruksi" >
                                                <div class="form-group col-md-12" >
                                                    <label>Instruksi / Pertanyaan</label>
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan[]" maxlength="500" placeholder="Tulis Instruksi" required></textarea>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <a href="javascript:;" id="line-instruksi" class="btn btn-danger btn-circle" style="cursor: not-allowed;opacity: 0.5;text-decoration: none;"><i class="fa fa-close"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-11">
                                                    <label>Type</label>
                                                    <select class="form-control input-sm" name="tipe_pilihan[]" required>
                                                        <option value="0">Single Choice</option>
                                                        <option value="1">Multiple Choice</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div id="instruksi">
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <button type="button" class="btn blue" id="btn_tambah_instruksi"><i class="fa fa-plus"></i> Tambah Instruksi</button>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Pilihan</label>
                                                <div id="line-pilihan">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" required> 
                                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="1" class="fa fa-trash btn_pilihan" id="delete_pilihan0" style="color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="pilihan"></div>

                                            <div class="form-group col-md-12">
                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan" id="btn_tambah_pilihan"><i class="fa fa-plus"></i> Tambah Pilihan</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $form;
                break;
            default:
                # code...
                break;
        }
    }

    public function addModalInterwiewer()
    {
        $modal_size = 'modal-sd';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i><span> Tambah</span> Interviewer';

        $modal_body = csrf_field()
                    .'  <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Username Interviewer</label>
                                    <input type="text" id="nama" maxlength="50" autocomplete="off" name="nama" class="form-control" placeholder="Username Interviewer">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                    ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="add"> <i class="fa fa-check"></i> Simpan </button>
                         <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function editModalInterviewer($idi, Request $Request)
    {
        $id = \Crypt::decryptString($idi);

        $interview = \DB::table('interviewer')->where('id',$id)->first();

        $modal_size = 'modal-sd';

        $modal_header = '<i class="fa fa-pencil" aria-hidden="true"></i><span> Edit</span> Interviewer';

        $modal_body = csrf_field()
                    .'  <input type="hidden" name="id" value="'.$idi.'">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Username Interviewer</label>
                                    <input type="text" id="nama" maxlength="50" autocomplete="off" name="nama" class="form-control" placeholder="Username Interviewer" value="'.$interview->nama.'">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                    ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="edit"> <i class="fa fa-check"></i> Simpan </button>
                         <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function detailModalResponden($survey_id, $id, Request $Request){
        // $data = \DB::table('view_responden_detail')->select('pertanyaan_id','text','pilihan','value')->where('survey_id',$survey_id)->where('responden_id',$id)->get()-;
        $detail = \DB::select(\DB::raw('SELECT * FROM view_responden_pertanyaan_detail WHERE survey_id = "'.$survey_id.'" AND responden_id = "'.$id.'" ORDER BY sequence'));
        // return $detail;
        foreach ($detail as $key => $value) {
            $pertanyaan[] = '<div class="col-md-12">
                                <div class="form-group">
                                    <label> <b>'.$value->nomor.'.</b> </label>
                                    <label> '.$value->text_pertanyaan.' </label>
                                    <input type="text" value="'.($value->pilihan != null ? \Iff::getPilihan($value->pilihan) : ($value->text != null ? $value->text : ($value->value != null ? $value->value : ''))).'" class="form-control" readonly>
                                </div>
                            </div>
    
                            ';
        }
        $responden = \DB::select(\DB::raw('SELECT * FROM responden WHERE id = "'.$id.'" AND deleted_at IS NULL LIMIT 1'));

        $modal_size = 'modal-sd';

        $modal_header = '<span> Detail</span> Responden';

        $modal_body = csrf_field()
                    .'  <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Nama </label>
                                    <span></span>
                                    <input type="text" value="'.$responden[0]->nama.'" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Telepon </label>
                                    <input type="text" value="'.$responden[0]->telepon.'" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Umur </label>
                                    <input type="text" value="'.$responden[0]->umur.'" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Email </label>
                                    <input type="text" value="'.$responden[0]->email.'" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Alamat </label>
                                    <textarea class="form-control" rows="4" readonly>'.$responden[0]->alamat.'</textarea>
                                </div>
                            </div>

                            <div class="modal-header">
                            <h4 class="modal-title"> '.\Iff::namaSurvey2($survey_id).' </h4>
                            </div>
                                <div class="modal-body">
                                    <div class="row">
                                    '.implode(' ', $pertanyaan).'
                                    </div>
                                </div>
                        </div>
                    ';

        $modal_footer = '
                         <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function tambahUser(Request $request)
    {
        $user  = \DB::table('view_user')->where('user_id',\Auth::user()->id)->first();
    
        $role = \DB::table('roles')->where('id','!=',1)->get();

        $option = '';
        foreach ($role as $dt_role => $value) {
            $option .= '<option value="'.$value->id.'">'.$value->display_name.'</option>';
        }
        $modal_size = 'modal-sd';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i><span> Tambah</span> User';

        $modal_body = csrf_field()
                    .'  <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" id="nama" maxlength="30" autocomplete="off" name="nama" class="form-control" placeholder="Nama User">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama user</label>
                                    <input type="text" id="username" maxlength="30" autocomplete="off" name="username" class="form-control" placeholder="Nama User">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hak Akses</label>
                                    <select name="role" class="form-control select2">
                                        '.$option.'
                                    </select>
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" id="password" maxlength="30" autocomplete="off" name="password" class="form-control" placeholder="Password">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" id="conf_pass" maxlength="30" autocomplete="off" name="conf_pass" class="form-control" placeholder="Konfirmasi Password">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                    ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="tambahUser"> <i class="fa fa-check"></i> Simpan </button>
                         <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);
        return $data;
    }

    public function editUser(Request $request)
    {
        $id = \Crypt::decryptString($request->id);

        $editUser = \DB::table('view_user')->where('user_id',$id)->first();
        
        $user  = \DB::table('view_user')->where('user_id',\Auth::user()->id)->first();
        
        $role = \DB::table('roles')->where('id','!=',1)->get();

        $option = '';
        foreach ($role as $dt_role => $value) {
            $option .= '<option value="'.$value->id.'">'.$value->display_name.'</option>';
        }
        $modal_size = 'modal-sd';

        $modal_header = '<i class="fa fa-plus" aria-hidden="true"></i><span> Tambah</span> User';

        $modal_body = csrf_field()
                    .'  <input type="hidden" name="id" value="'.$request->id.'">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" id="nama" maxlength="30" autocomplete="off" name="nama" class="form-control" placeholder="Nama User">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama user</label>
                                    <input type="text" id="username" maxlength="30" autocomplete="off" name="username" class="form-control" placeholder="Nama User">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hak Akses</label>
                                    <select name="role" id="role" class="form-control select2">
                                        '.$option.'
                                    </select>
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" id="password" maxlength="30" autocomplete="off" name="password" class="form-control" placeholder="Password">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" id="conf_pass" maxlength="30" autocomplete="off" name="conf_pass" class="form-control" placeholder="Konfirmasi Password">
                                    <span class="help-block has-error"></span>    
                                </div>
                            </div>
                        </div>
                    ';

        $modal_footer = '<button type="submit" class="btn btn-primary" id="btn-save" value="editUser"> <i class="fa fa-check"></i> Simpan </button>
                         <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>';

        $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer, 'user'=>$editUser);
        return $data;
    }
}