<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Log, DB;

class FormController extends Controller
{
    public function __construct() {

        $this->user_id = (isset(session('user')[0]->token['user_id']) ? session('user')[0]->token['user_id'] : '');
    }
    public function saveSurvey(Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:survey,nama,NULL,id,deleted_at,NULL',
            'durasi' => 'required'
        ],[

        ]);

        $input = $request->all();

        Log::notice(' =============================== End Save CRUD /survey =================================== ');
        Log::info(json_encode($input));
        Log::info('user_id : '.$this->user_id);
        Log::notice(' ================================= Save CRUD /survey =================================== ');

        $data = \DB::table('survey')->insert([
            'nama' => $input['nama'],
            'durasi' => $input['durasi'],
            'user_id' => \Auth::user()->id,
            'is_reusable' => isset($request->reusable) ? 2 : 0,
        ]);
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
         ]);
    }

    public function updateSurvey($id,Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:survey,nama,'.$id.',id,deleted_at,NULL',
            'durasi' => 'required'
        ],[

        ]);

        $input = $request->all();

        Log::notice(' =============================== End Update CRUD /survey =================================== ');
        Log::info(json_encode($input));
        Log::info('user_id : '.$this->user_id);
        Log::notice(' ================================= Update CRUD /survey =================================== ');

        $data = \DB::table('survey')->where('id',$id)->update([
            'nama' => $input['nama'],
            'durasi' => $input['durasi'],
            'user_id' => \Auth::user()->id,
        ]);
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
         ]);
    }

    public function copySurvey($id,Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:survey,nama,NULL,id,deleted_at,NULL',
            'durasi' => 'required'
        ],[

        ]);        
        $input = $request->all();

        Log::notice(' =============================== End Copy survey =================================== ');
        Log::info(json_encode($input));
        Log::info('user_id : '.$this->user_id);
        Log::notice(' ================================= Copy survey =================================== ');
        DB::transaction(function() use($id,$request) {
            //save_survey
            DB::statement("INSERT INTO survey (
                nama,durasi,is_reusable,skor,soal_resp,klasifikasi,user_id,rec_usr,deleted_at,created_at,updated_at)
                SELECT '$request->nama',$request->durasi,is_reusable,skor,soal_resp,klasifikasi,$this->user_id,$this->user_id,null,Now(),Now()
                FROM survey
                WHERE id=$id");
            $survey_id = DB::getPdo()->lastInsertId();

            //save klasikasi skor
            DB::statement("INSERT INTO klasifikasi_skor (
                klasifikasi,survey_id,logika,nilai_a,nilai_b,deleted_at,created_at,updated_at)
                SELECT klasifikasi,$survey_id,logika,nilai_a,nilai_b,null,Now(),Now()
                FROM klasifikasi_skor
                WHERE survey_id=$id");
                
            //save soal_responden
            DB::statement("INSERT INTO soal_responden (
                survey_id,tipe,is_required,label,value,deleted_at,created_at,updated_at)
                SELECT $survey_id,tipe,is_required,label,value,null,Now(),Now()
                FROM soal_responden
                WHERE survey_id=$id");

            $pertanyaan_survey = \DB::select('SELECT id, pertanyaan_id 
                FROM pertanyaan_survey 
                WHERE survey_id = '.$id.' 
                AND deleted_at IS NULL');

            $arr_id_pertanyaan=[];
            $arr_id_pertanyaan[0]="0";
            $arr_id_pilihan=[];
            foreach ($pertanyaan_survey as $key => $value) {
                //save pertanyaan
                DB::statement("INSERT INTO pertanyaan (text,jenis_id,nomor,rec_usr,deleted_at,created_at,updated_at)
                    SELECT text,jenis_id,nomor,$this->user_id,null,Now(),Now()
                    FROM pertanyaan
                    WHERE id=$value->id");
                $pertanyaan_id = DB::getPdo()->lastInsertId();
                $arr_id_pertanyaan[$value->id] = $pertanyaan_id;

                //save pertanyaan survey
                DB::statement("INSERT INTO pertanyaan_survey (
                    survey_id,pertanyaan_id,sequence,static,position,is_klasifikasi,rec_usr,deleted_at,created_at,updated_at)
                    SELECT $survey_id,$pertanyaan_id,sequence,static,position,is_klasifikasi,$this->user_id,null,Now(),Now()
                    FROM pertanyaan_survey
                    WHERE id=$value->id");

                //save pertanyaan survey
                DB::statement("INSERT INTO pilihan_multiple (
                    pertanyaan_id,is_multiple)
                    SELECT $pertanyaan_id,is_multiple
                    FROM pilihan_multiple
                    WHERE id=$value->id");

                //get data pilihan
                $pilihan = DB::select("SELECT * FROM pilihan WHERE pertanyaan_id = $value->pertanyaan_id AND deleted_at IS NULL");
                if (!empty($pilihan)) {
                    foreach ($pilihan as $key => $value2) {
                        DB::statement("INSERT INTO pilihan (
                            text,bobot,pertanyaan_id,is_required,rec_usr,deleted_at,created_at,updated_at)
                            SELECT text,bobot,$pertanyaan_id,is_required,$this->user_id,null,Now(),Now()
                            FROM pilihan
                            WHERE id=$value2->id");
                        $pilihan_id = DB::getPdo()->lastInsertId();
                        $arr_id_pilihan[$value2->id] = $pilihan_id;
                    }
                }
            }
            $data_anti = [];
            $data_rule = [];
            $data_instruksi = [];
            foreach ($pertanyaan_survey as $key => $value) {
               $rules = DB::select("SELECT * FROM rule WHERE pertanyaan_id = $value->id AND deleted_at IS NULL");
               if (!empty($rules)) {
                    foreach ($rules as $key => $rule) {
                        $data_rule[] = [
                            'pertanyaan_id' => $arr_id_pertanyaan[$rule->pertanyaan_id],
                            'pilihan_id' => $arr_id_pilihan[$rule->pilihan_id],
                            'next_id' => $arr_id_pertanyaan[$rule->next_id],
                            'scale' => $rule->scale,
                            'rec_usr' => $this->user_id,
                            'deleted_at' => NULL,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()];
                    }
                }
               $antis = DB::select("SELECT * FROM anti_pair WHERE pertanyaan_id = $value->id AND deleted_at IS NULL");
               if (!empty($antis)) {
                   foreach ($antis as $key => $anti) {
                    $data_anti[] = [
                        'pertanyaan_id' => $arr_id_pertanyaan[$anti->pertanyaan_id],
                        'pilihan_id' => $arr_id_pilihan[$anti->pilihan_id],
                        'pilihan_id_anti' => $arr_id_pilihan[$anti->pilihan_id_anti],
                        'rec_usr' => $this->user_id,
                        'deleted_at' => NULL,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()];
                    }
                }

               $instruksis = DB::select("SELECT * FROM instruksi WHERE pertanyaan_id = $value->id AND deleted_at IS NULL");
               if (!empty($instruksis)) {
                    foreach ($instruksis as $key => $instruksi) {
                        $data_instruksi[] = [
                            'pertanyaan_id' => $arr_id_pertanyaan[$instruksi->pertanyaan_id],
                            'text' => $instruksi->text,
                            'rec_usr' => $this->user_id,
                            'deleted_at' => NULL,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()];
                    }
                }
            }
            $chuck_rule = array_chunk($data_rule,50);
            $chuck_anti = array_chunk($data_anti,50);
            $chuck_instruksi = array_chunk($data_instruksi,50);
            if (!empty($data_rule)) {
                foreach ($chuck_rule as $key => $value) {
                    DB::table('rule')->insert($value);
                }
            }
            if (!empty($data_anti)) {
                foreach ($chuck_anti as $key => $value) {
                    DB::table('anti_pair')->insert($value);
                }
            }
            if (!empty($chuck_instruksi)) {
                foreach ($data_instruksi as $key => $value) {
                    DB::table('instruksi')->insert($value);
                }
            }
        });
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
         ]);
    }

    public function deleteSurvey($ids,Request $request){
        $id = Crypt::decryptString($ids);

        Log::notice(' =============================== End Delete CRUD /survey =================================== ');
        Log::info('user_id : '.$this->user_id);
        Log::info('id_survey : '.$id);
        Log::notice(' ================================= Delete CRUD /survey =================================== ');

        \DB::table('pertanyaan_survey')->where('survey_id',$id)->update([
            'deleted_at' => \Carbon\Carbon::now(),
        ]);
        \DB::table('survey')->where('id',$id)->update([
            'deleted_at' => \Carbon\Carbon::now(),
        ]);
        return response()->json([
            'message' => 'Sukses menghapus data.',
            'status' => 'success',
            'status_code' => 200
         ]);

    }

    public function saveDevices(Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:device,nama,NULL,id,deleted_at,NULL',
            'imei' => 'required|unique:device,imei,NULL,id,deleted_at,NULL',
        ],[

        ]);
        
        try {
            $input = $request->all();

            Log::notice(' =============================== End Save CRUD /devices =================================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Save CRUD /devices =================================== ');

            $data = \DB::table('device')->insert([
                'nama' => $input['nama'],
                'keterangan' => $input['keterangan'],
                'imei' => $input['imei']
            ]);
            return response()->json([
                'message' => 'Sukses menyimpan data.',
                'status' => 'success',
                'status_code' => 200
             ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateDevices($id,Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:device,nama,'.$id.',id,deleted_at,NULL',
            'imei' => 'required|unique:device,imei,'.$id.',id,deleted_at,NULL',
        ],[

        ]);
        
        try {
            $input = $request->all();

            Log::notice(' =============================== End Update CRUD /devices =================================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Update CRUD /devices =================================== ');

            $data = \DB::table('device')->where('id',$id)->update([
                'nama' => $input['nama'],
                'keterangan' => $input['keterangan'],
                'imei' => $input['imei']
            ]);

            return response()->json([
                'message' => 'Sukses menyimpan data.',
                'status' => 'success',
                'status_code' => 200
            ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteDevicesSurvey($id,Request $request){

        Log::notice(' =============================== End Delete Link Survey CRUD /devices =================================== ');
        Log::info('user_id : '.$this->user_id);
        Log::info('devices_id : '.$id);
        Log::notice(' ================================= Delete Link Survey CRUD /devices =================================== ');

        \DB::table('device')->where('survey_id',$id)->update([
            'survey_id' => NULL,
        ]);
        return response()->json([
            'message' => 'Sukses menghapus data.',
            'status' => 'success',
            'status_code' => 200
        ]);
    }

    public function deleteDevices($id,Request $request){

        Log::notice(' =============================== End Delete CRUD /devices =================================== ');
        Log::info('user_id : '.$this->user_id);
        Log::info('devices_id : '.$id);
        Log::notice(' ================================= Delete CRUD /devices =================================== ');

        \DB::table('device')->where('id',$id)->update([
            'deleted_at' => \Carbon\Carbon::now(),
        ]);
        return response()->json([
            'message' => 'Sukses menghapus data.',
            'status' => 'success',
            'status_code' => 200
         ]);
    }


    public function pertanyaan($ids,Request $request){
        \DB::transaction(function () use ($request, $ids) {
            $id = Crypt::decryptString($ids);
            $input = $request->all();
            Log::notice(' =============================== End Save Survey =================================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Save Survey =================================== ');

            $sequence = $request->get('sequence');

            $survey = \DB::select(\DB::raw('SELECT id, pertanyaan_id, survey_id, deleted_at 
                                            FROM pertanyaan_survey 
                                            WHERE survey_id = '.$id.' 
                                            AND deleted_at IS NULL'));

            foreach ($survey as $key => $value) {
                \DB::table('pertanyaan')->where('id',$value->pertanyaan_id)->update([
                    'deleted_at' => \Carbon\Carbon::now(),
                ]);
                \DB::table('pertanyaan_survey')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                    'deleted_at' => \Carbon\Carbon::now(),
                ]);
                \DB::table('rule')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                    'deleted_at' => \Carbon\Carbon::now(),
                ]);
                \DB::table('pertanyaan_scale')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                    'deleted_at' => \Carbon\Carbon::now(),
                ]);
                \DB::table('pilihan')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                    'deleted_at' => \Carbon\Carbon::now(),
                ]);
                \DB::table('anti_pair')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                    'deleted_at' => \Carbon\Carbon::now(),
                ]);
                // $data[] = $value;
            }

            // return $data;
            $urutan = 1;
            foreach ($sequence as $key => $seq) {
                $tipe = $input['tipe'][$seq][0];
                switch ($tipe) {
                    case '1':
                    case '2':
                        if(isset($input['id_pertanyaan'][$seq][0])){
                            $pertanyaan = $input['id_pertanyaan'][$seq][0];
                            \DB::table('pertanyaan')->where('id',$pertanyaan)->update([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                            DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan)
                            ->update([
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                        }else{
                            $pertanyaan = \DB::table('pertanyaan')->insertGetId([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            \DB::table('pertanyaan_survey')->insert([
                                'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                'pertanyaan_id' => $pertanyaan,
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }


                        if (isset($input['pilihan'][$seq])) {
                            foreach ($input['pilihan'][$seq] as $key_pil => $pil) {
                                $is_req = 0;
                                if (isset($input['is_req_pilihan'][$seq][0])) {
                                    if ($key_pil == ($input['is_req_pilihan'][$seq][0] - 1)) {
                                        $is_req = 1;
                                    }
                                }
                                if(isset($input['id_pilihan'][$seq][$key_pil])){
                                    $pilihan[$seq][$pil] = $input['id_pilihan'][$seq][$key_pil];
                                    \DB::table('pilihan')->where('id',$input['id_pilihan'][$seq][$key_pil])->update([
                                        'text' => $pil,
                                        'pertanyaan_id' => $pertanyaan,
                                        'is_required' => $is_req,
                                        'updated_at' => Carbon::now(),
                                        'deleted_at' => NULL,
                                    ]);
                                }else{
                                    $pilihan[$seq][$pil] = \DB::table('pilihan')->insertGetId([
                                        'text' => $pil,
                                        'pertanyaan_id' => $pertanyaan,
                                        'is_required' => $is_req,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]);
                                }
                            }
                        }
                        
                        $pertanyaan_id[$seq] =  $pertanyaan;
                        $pilihan_id[$seq] =  $pilihan;

                        $urutan++;
                    break;
                    
                    case '4':
                        if(isset($input['id_pertanyaan'][$seq][0])){
                            $pertanyaan = $input['id_pertanyaan'][$seq][0];
                            \DB::table('pertanyaan')->where('id',$pertanyaan)->update([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                            DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan)
                            ->update([
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                        }else{
                            $pertanyaan = \DB::table('pertanyaan')->insertGetId([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            \DB::table('pertanyaan_survey')->insert([
                                'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                'pertanyaan_id' => $pertanyaan,
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                        if (isset($input['pilihan'][$seq])) {
                            $no = 1;
                            foreach ($input['pilihan'][$seq] as $key_pil => $pil) {
                                if(isset($input['id_pilihan'][$seq][$key_pil])){
                                    $pilihan[$seq][$pil] = $input['id_pilihan'][$seq][$key_pil];
                                    \DB::table('pilihan')->where('id',$input['id_pilihan'][$seq][$key_pil])->update([
                                        'text' => $pil,
                                        'pertanyaan_id' => $pertanyaan,
                                        'updated_at' => Carbon::now(),
                                        'deleted_at' => NULL,
                                        'bobot' => $no,
                                    ]);
                                }else{
                                    $pilihan[$seq][$pil] = \DB::table('pilihan')->insertGetId([
                                        'text' => $pil,
                                        'pertanyaan_id' => $pertanyaan,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                        'bobot' => $no,
                                    ]);
                                }
                                $no++;
                            }
                        }
                        
                        $pertanyaan_id[$seq] =  $pertanyaan;
                        $pilihan_id[$seq] =  $pilihan;

                        $urutan++;
                    break;
                    case '3':
                        if(isset($input['id_pertanyaan'][$seq][0])){
                            $pertanyaan3 = $input['id_pertanyaan'][$seq][0];
                            \DB::table('pertanyaan')->where('id',$pertanyaan3)->update([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                            DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan3)
                            ->update([
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                        }else{
                            $pertanyaan3 = \DB::table('pertanyaan')->insertGetId([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            \DB::table('pertanyaan_survey')->insert([
                                'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                'pertanyaan_id' => $pertanyaan3,
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }

                        if (isset($input['pilihan'][$seq])) {
                            foreach ($input['pilihan'][$seq] as $key_pil => $pil) {

                                $is_req = 0;
                                if (isset($input['is_req_pilihan'][$seq][$key_pil])) {
                                        $is_req = 1;
                                }

                                if(isset($input['id_pilihan'][$seq][$key_pil])){
                                    $pilihan3[$seq][$pil] = $input['id_pilihan'][$seq][$key_pil];
                                    \DB::table('pilihan')->where('id',$input['id_pilihan'][$seq][$key_pil])->update([
                                        'text' => $pil,
                                        'pertanyaan_id' => $pertanyaan3,
                                        'is_required' => $is_req,
                                        'updated_at' => Carbon::now(),
                                        'deleted_at' => NULL,
                                    ]);
                                }else{
                                    $pilihan3[$seq][$pil] = \DB::table('pilihan')->insertGetId([
                                        'text' => $pil,
                                        'pertanyaan_id' => $pertanyaan3,
                                        'is_required' => $is_req,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]);
                                }
                            }
                        }
                        if (isset($input['option_end'][$seq]) && $input['option_end'][$seq] != NULL) {
                            foreach($input['option_end'][$seq] as $key_opt_end => $opt_end){
                                \DB::table('rule')->insert([
                                    'pertanyaan_id' => $pertanyaan3,
                                    'pilihan_id' => $pilihan3[$seq][$opt_end],
                                    'next_id' => '0',
                                ]);
                            }
                        }
                        if (isset($input['select_anti_pilihan'][$seq]) && $input['select_anti_pilihan'][$seq] !== NULL) {
                            foreach($input['select_anti_pilihan'][$seq] as $key_anti => $anti){
                                if ($anti == NULL) {
                                    continue;
                                }
                                \DB::table('anti_pair')->insert([
                                    'pertanyaan_id' => $pertanyaan3,
                                    'pilihan_id' => $pilihan3[$seq][$anti],
                                    'pilihan_id_anti' => $pilihan3[$seq][$input['select_anti_pilihan2'][$seq][$key_anti]],
                                ]);   
                            }
                        }

                        $pertanyaan_id[$seq] =  $pertanyaan3;
                        $urutan++;
                    break;
                    case '5':
                        if(isset($input['id_pertanyaan'][$seq][0])){
                            $pertanyaan5 = $input['id_pertanyaan'][$seq][0];
                            \DB::table('pertanyaan')->where('id',$pertanyaan5)->update([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                            DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan5)
                            ->update([
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                        }else{
                            $pertanyaan5 = \DB::table('pertanyaan')->insertGetId([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            \DB::table('pertanyaan_survey')->insert([
                                'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                'pertanyaan_id' => $pertanyaan5,
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                        if(isset($input['id_pilihan'][$seq][0])){
                            $pilihan[$seq][$pil] = $input['id_pilihan'][$seq][0];
                            \DB::table('pilihan')->where('id',$input['id_pilihan'][$seq][0])->update([
                                'text' => implode('|||', $input['pilihan'][$seq]),
                                'bobot' => $input['scale'][$seq][0],
                                'pertanyaan_id' => $pertanyaan5,
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                        }else{
                            \DB::table('pilihan')->insertGetId([
                                    'text' => implode('|||', $input['pilihan'][$seq]),
                                    'bobot' => $input['scale'][$seq][0],
                                    'pertanyaan_id' => $pertanyaan5,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                            ]);
                        }
                        $pertanyaan_id[$seq] =  $pertanyaan5;
                        $urutan++;
                    break;
                    case '6':
                        if(isset($input['id_pertanyaan'][$seq][0])){
                            $pertanyaan6 = $input['id_pertanyaan'][$seq][0];
                            \DB::table('pertanyaan')->where('id',$pertanyaan6)->update([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                            DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan6)
                            ->update([
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'updated_at' => Carbon::now(),
                                'deleted_at' => NULL,
                            ]);
                        }else{
                            $pertanyaan6 = \DB::table('pertanyaan')->insertGetId([
                                'text' => $input['pertanyaan'][$seq][0],
                                'jenis_id' => $input['tipe'][$seq][0],
                                'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            \DB::table('pertanyaan_survey')->insert([
                                'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                'pertanyaan_id' => $pertanyaan6,
                                'sequence' => $urutan,
                                'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }

                            $pertanyaan_id[$seq] =  $pertanyaan6;
                            $urutan++;

                        break;
                    case '7':
                        foreach ($input['pertanyaan'][$seq] as $key_per => $per) {
                            if(isset($input['id_pertanyaan'][$seq][$key_per])){
                                $pertanyaan7 = $input['id_pertanyaan'][$seq][$key_per];
                                \DB::table('pertanyaan')->where('id',$pertanyaan7)->update([
                                    'text' => $per,
                                    'jenis_id' => $input['tipe'][$seq][0],
                                    'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                    'updated_at' => Carbon::now(),
                                    'deleted_at' => NULL,
                                ]);
                                DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan7)
                                ->update([
                                    'sequence' => $urutan,
                                    'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                    'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                    'updated_at' => Carbon::now(),
                                    'deleted_at' => NULL,
                                ]);

                                \DB::table('instruksi')->where('pertanyaan_id',$pertanyaan7)->update([
                                    'text' => $input['instruksi'][$seq],
                                    'updated_at' => Carbon::now(),
                                    'deleted_at' => NULL,
                                ]);

                                foreach ($input['pilihan'][$seq] as $key_pil => $pil) {
                                    if(isset($input['id_pilihan'][$seq][$key_pil])){
                                        $arrayIdPil = explode(',',$input['id_pilihan'][$seq][$key_pil]);
                                        $cek =  \DB::table('pilihan')->where('id',$arrayIdPil[$key_per])->first();
                                        $count =  \DB::table('pilihan')->where('id',$arrayIdPil[$key_per])->count();
                                        if(isset($arrayIdPil[$key_per]) && $count == 1 && $cek->pertanyaan_id == $pertanyaan7){
                                            \DB::table('pilihan')->where('id',$arrayIdPil[$key_per])->update([
                                                'text' => $pil,
                                                'pertanyaan_id' => $pertanyaan7,
                                                'updated_at' => Carbon::now(),
                                                'deleted_at' => NULL,
                                            ]);
                                        }
                                    }else{
                                        $pilihan[$seq][$pil] = \DB::table('pilihan')->insertGetId([
                                            'text' => $pil,
                                            'pertanyaan_id' => $pertanyaan7,
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                        ]);
                                    }
                                }

                            }else{
                                $pertanyaan7 = \DB::table('pertanyaan')->insertGetId([
                                    'text' => $input['pertanyaan'][$seq][$key_per],
                                    'jenis_id' => $input['tipe'][$seq][0],
                                    'nomor' => $input['nomor_pertanyaan'][$seq][0],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);

                                \DB::table('instruksi')->insert([
                                    'text' => $input['instruksi'][$seq],
                                    'pertanyaan_id' => $pertanyaan7,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);

                                foreach ($input['pilihan'][$seq] as $key_pil => $pil) {
                                    \DB::table('pilihan')->insertGetId([
                                        'text' => $input['pilihan'][$seq][$key_pil],
                                        'pertanyaan_id' => $pertanyaan7,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]);
                                }

                                \DB::table('pertanyaan_survey')->insert([
                                    'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                    'pertanyaan_id' => $pertanyaan7,
                                    'sequence' => $urutan,
                                    'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                    'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }
                        }
                        $pertanyaan_id[$seq] =  $pertanyaan7;
                        $urutan++;

                    break;
                    case '8':
                        foreach ($input['pertanyaan'][$seq] as $key_per => $per) {

                            if(isset($input['id_pertanyaan'][$seq][$key_per])){
                                $pertanyaan8 = $input['id_pertanyaan'][$seq][$key_per];
                                \DB::table('pertanyaan')->where('id',$pertanyaan8)->update([
                                    'text' => $per,
                                    'jenis_id' => $input['tipe'][$seq][0],
                                    'nomor' => $input['nomor_pertanyaan'][$seq][$key_per],
                                    'updated_at' => Carbon::now(),
                                    'deleted_at' => NULL,
                                ]);
                                DB::table('pertanyaan_survey')->where('survey_id',Crypt::decryptString($request->get('survey_id')))->where('pertanyaan_id',$pertanyaan8)
                                ->update([
                                    'sequence' => $urutan,
                                    'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                    'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                    'updated_at' => Carbon::now(),
                                    'deleted_at' => NULL,
                                ]);

                                foreach ($input['pilihan'][$seq] as $key_pil => $pil) {
                                    if(isset($input['id_pilihan'][$seq][$key_pil])){
                                        $arrayIdPil = explode(',',$input['id_pilihan'][$seq][$key_pil]);
                                        $cek =  \DB::table('pilihan')->where('id',$arrayIdPil[$key_per])->first();
                                        $count =  \DB::table('pilihan')->where('id',$arrayIdPil[$key_per])->count();
                                        if(isset($arrayIdPil[$key_per]) && ($count) == 1 && $cek->pertanyaan_id == $pertanyaan8){
                                            \DB::table('pilihan')->where('id',$arrayIdPil[$key_per])->update([
                                                'text' => $pil,
                                                'pertanyaan_id' => $pertanyaan8,
                                                'updated_at' => Carbon::now(),
                                                'deleted_at' => NULL,
                                            ]);
                                        }
                                    }else{
                                        $pilihan[$seq][$pil] = \DB::table('pilihan')->insertGetId([
                                            'text' => $pil,
                                            'pertanyaan_id' => $pertanyaan8,
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                        ]);
                                    }
                                }

                            }else{
                                $pertanyaan8 = \DB::table('pertanyaan')->insertGetId([
                                    'text' => $input['pertanyaan'][$seq][$key_per],
                                    'jenis_id' => $input['tipe'][$seq][0],
                                    'nomor' => $input['nomor_pertanyaan'][$seq][$key_per],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);

                                foreach ($input['pilihan'][$seq] as $key_pil => $pil) {
                                    \DB::table('pilihan')->insertGetId([
                                        'text' => $input['pilihan'][$seq][$key_pil],
                                        'pertanyaan_id' => $pertanyaan8,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]);
                                }

                                \DB::table('pertanyaan_survey')->insert([
                                    'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                    'pertanyaan_id' => $pertanyaan8,
                                    'sequence' => $urutan,
                                    'static' => ( isset($input['static_content'][$seq][0]) ? $input['static_content'][$seq][0] : NULL),
                                    'position' => ( isset($input['static_content'][$seq][0]) ? $input['posisi'][$seq][0] : NULL),
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }
                            $cek = \DB::table('pilihan_multiple')->where('pertanyaan_id', $pertanyaan8)->count();
                            if ($cek > 0) {
                                \DB::table('pilihan_multiple')->where('pertanyaan_id', $pertanyaan8)->delete();
                            }
                            if ($input['tipe_pilihan'][$seq][$key_per] == 1) {
                                \DB::table('pilihan_multiple')->insert([
                                    'pertanyaan_id' => $pertanyaan8,
                                    'is_multiple' => 1,
                                ]);
                            }
                        }

                        $pertanyaan_id[$seq] =  $pertanyaan8;
                        $urutan++;

                        // return $pertanyaan8;
                    break;
                    case '9':
                        $no_seq = 1;
                        if (isset($input['jenis_input'][$seq])) {
                            foreach($input['pertanyaan'][$seq] as $k => $val){

                                $jenis_input = $input['jenis_input'][$seq][$k][0];

                                $pertanyaan = \DB::table('pertanyaan')->insertGetId([
                                                    'text' => $val[0],
                                                    'jenis_id' => 9,
                                                    'id_type' => $jenis_input,
                                                ]);
                                \DB::table('pertanyaan_survey')->insert([
                                    'survey_id' => Crypt::decryptString($request->get('survey_id')),
                                    'pertanyaan_id' => $pertanyaan,
                                    'sequence' => $urutan,
                                    'sequence_reusable' => $no_seq,
                                ]);

                                if (($jenis_input == 3) || ($jenis_input == 4)) {
                                    foreach ($input['pilihan'][$seq][$k] as $key => $value) {

                                        \DB::table('pilihan')->insertGetId([
                                            'text' => $value,
                                            'pertanyaan_id' => $pertanyaan,
                                        ]);

                                    }
                                }

                                $no_seq++;

                            }
                        }

                        $urutan++;
                    break;
                }
                // return $input['select_rule_pilihan'][$seq];
            }

            // return $pilihan;
                            // return $pertanyaan_id;
            foreach ($sequence as $key => $urut) {
                $tipe1 = $input['tipe'][$urut][0];
                if ($tipe1 != '5') {
                    if (isset($input['select_rule_pilihan'][$urut])) {
                        foreach ($input['select_rule_pilihan'][$urut] as $key => $slp) {
                            if ($input['rules'][$urut][$key] == 'go') {
                                \DB::table('rule')->insert([
                                    'pertanyaan_id' => $pertanyaan_id[$urut],
                                    'pilihan_id' => $pilihan[$urut][$slp],
                                    'next_id' => $pertanyaan_id[$input['select_rule_pertanyaan'][$urut][$key]],
                                ]);
                            }else if ($input['rules'][$urut][$key] == 'end') {
                                \DB::table('rule')->insert([
                                    'pertanyaan_id' => $pertanyaan_id[$urut],
                                    'pilihan_id' => $pilihan[$urut][$slp],
                                    'next_id' => '0',
                                ]);
                            }
                        }
                    }
                }else if($tipe1 == '5'){
                    if (isset($input['select_rule_pilihan'][$urut])) {
                        // return $pertanyaan_id[$input['select_rule_pertanyaan'][$urut][0]];
                        foreach ($input['select_rule_pilihan'][$urut] as $key2 => $slp) {
                            // return $input['select_rule_pilihan'][$urut];
                            // return $input['select_rule_pertanyaan'][$urut][$key2];
                            if ($input['rules'][$urut][$key2] == 'go') {
                                \DB::table('rule')->insert([
                                    'pertanyaan_id' => $pertanyaan_id[$urut],
                                    'scale' => $slp,
                                    'next_id' => $pertanyaan_id[$input['select_rule_pertanyaan'][$urut][$key2]],
                                ]);
                            }else if ($input['rules'][$urut][$key2] == 'end') {
                                \DB::table('rule')->insert([
                                    'pertanyaan_id' => $pertanyaan_id[$urut],
                                    'scale' => $slp,
                                    'next_id' => '0',
                                ]);
                            }
                        }
                    }
                }
        }
    });
        $id = Crypt::decryptString($ids);
        $cek = \DB::table('survey')->where('id',$id)->first()->is_reusable;
        $link = '/survey';
        if ($cek == 2) {
            $link = '/survey-reus';
        }
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'link' => $link,
            'status' => 'success',
            'status_code' => 200
         ]);

    }

    public function savePertanyaan(Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:survey,nama,NULL,id,deleted_at,NULL',
        ],[

        ]);
        
        try {
            $input = $request->all();

            Log::notice(' ======================== End Save Survey Reusable /pertanyaan ============================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ========================== Save Survey Reusable /pertanyaan ============================== ');

            $data = \DB::table('survey')->insert([
                'nama' => $input['nama'],
                'durasi' => 0,
                'user_id' => \Auth::user()->id,
                'is_reusable' => 1,
            ]);
            return response()->json([
                'message' => 'Sukses menyimpan data.',
                'status' => 'success',
                'status_code' => 200
             ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updatePertanyaan($id,Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:survey,nama,'.$id.',id,deleted_at,NULL',
        ],[

        ]);
        
        try {
            $input = $request->all();

            Log::notice(' ======================== End Update Survey Reusable /pertanyaan ============================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ========================== Update Survey Reusable /pertanyaan ============================== ');

            $data = \DB::table('survey')->where('id',$id)->update([
                'nama' => $input['nama'],
                'durasi' => 0,
                'user_id' => \Auth::user()->id,
                'is_reusable' => 1,
            ]);
            return response()->json([
                'message' => 'Sukses menyimpan data.',
                'status' => 'success',
                'status_code' => 200
             ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deletePertanyaan($ids,Request $request){
        $id = Crypt::decryptString($ids);

        Log::notice(' ======================== End Delete Survey Reusable /pertanyaan ============================== ');
        Log::info('survey_id : '.$id);
        Log::info('user_id : '.$this->user_id);
        Log::notice(' ========================== Delete Survey Reusable /pertanyaan ============================== ');

        \DB::table('pertanyaan_survey')->where('survey_id',$id)->update([
            'deleted_at' => \Carbon\Carbon::now(),
        ]);
        \DB::table('survey')->where('id',$id)->update([
            'deleted_at' => \Carbon\Carbon::now(),
        ]);
        return response()->json([
            'message' => 'Sukses menghapus data.',
            'status' => 'success',
            'status_code' => 200
         ]);

    }

    public function reusable($idr, Request $request)
    {
        $jenis = $request->jenis;
        $id = Crypt::decryptString($idr);
        $input = $request->all();

        Log::notice(' ======================== End Simpan Pertanyaan Reusable /reusable ============================== ');
        Log::info(json_encode($input));
        Log::info('user_id : '.$this->user_id);
        Log::notice(' ========================== Simpan Pertanyaan Reusable /reusable ============================== ');

        $reusable = \DB::select(\DB::raw('SELECT id, pertanyaan_id, survey_id, deleted_at 
                                        FROM pertanyaan_survey 
                                        WHERE survey_id = '.$id.' 
                                        AND deleted_at IS NULL'));
        
        foreach ($reusable as $key => $value) {
            \DB::table('pertanyaan')->where('id',$value->pertanyaan_id)->update([
                'deleted_at' => \Carbon\Carbon::now(),
            ]);
            
            \DB::table('pertanyaan_survey')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                'deleted_at' => \Carbon\Carbon::now(),
            ]);

            \DB::table('pilihan')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                'deleted_at' => \Carbon\Carbon::now(),
            ]);

            \DB::table('pertanyaan_scale')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                'deleted_at' => \Carbon\Carbon::now(),
            ]);

            \DB::table('instruksi')->where('pertanyaan_id',$value->pertanyaan_id)->update([
                'deleted_at' => \Carbon\Carbon::now(),
            ]);
        }

        switch ($jenis) {
            case '1':
            case '2':
            case '3':
            case '4':
            case '6':
                $reusable = \DB::table('pertanyaan')->insertGetId([
                    'text' => $request['pertanyaan'],
                    'jenis_id' => $request['jenis'],
                    'rec_usr' => \Auth::user()->id
                ]);

                \DB::table('pertanyaan_survey')->insert([
                    'survey_id' => $id,
                    'pertanyaan_id' => $reusable,
                    'rec_usr' => \Auth::user()->id
                ]);

                if (isset($request['pilihan'])) {
                    foreach ($request['pilihan'] as $key => $pil) {
                        $pilihan = \DB::table('pilihan')->insertGetId([
                            'text' => $request['pilihan'][$key],
                            'pertanyaan_id' => $reusable,
                            'rec_usr' => \Auth::user()->id
                        ]);
                    }
                }
                // dd('oke');
                break;
            case '5':
                $reusable = \DB::table('pertanyaan')->insertGetId([
                    'text' => $request['pertanyaan'],
                    'jenis_id' => $request['jenis'],
                    'rec_usr' => \Auth::user()->id
                ]);

                \DB::table('pertanyaan_survey')->insert([
                    'survey_id' => $id,
                    'pertanyaan_id' => $reusable,
                    'rec_usr' => \Auth::user()->id
                ]);

                if (isset($request['pilihan'])) {
                    
                    $pilihan_imp = implode("|||",$request['pilihan']);
                    
                    $pilihan = \DB::table('pilihan')->insertGetId([
                        'text' => $pilihan_imp,
                        'bobot' => $request['scale'],
                        'pertanyaan_id' => $reusable,
                        'rec_usr' => \Auth::user()->id
                    ]);
                }
                // dd('oke2');
                break;
            case '7':
                foreach ($request['pertanyaan'] as $key => $value) {
                    $reusable = \DB::table('pertanyaan')->insertGetId([
                        'text' => $request['pertanyaan'][$key],
                        'jenis_id' => $request['jenis'],
                        'rec_usr' => \Auth::user()->id
                    ]);

                    foreach ($request['pilihan'] as $key_pil => $pil) {
                        $pilihan = \DB::table('pilihan')->insertGetId([
                            'text' => $request['pilihan'][$key_pil],
                            'pertanyaan_id' => $reusable,
                            'rec_usr' => \Auth::user()->id
                        ]);
                    }

                    \DB::table('instruksi')->insert([
                        'text' => $request['instruksi'],
                        'pertanyaan_id' => $reusable,
                    ]);

                    \DB::table('pertanyaan_survey')->insert([
                        'survey_id' => $id,
                        'pertanyaan_id' => $reusable,
                        'rec_usr' => \Auth::user()->id
                    ]);

                    // \DB::table('pertanyaan_scale')->insert([
                    //     'value' => count($request['pilihan']),
                    //     'pertanyaan_id' => $reusable
                    // ]);
                }
                // dd('oke3');
                break;
            case '8':
                foreach ($request['pertanyaan'] as $key => $value) {
                    $reusable = \DB::table('pertanyaan')->insertGetId([
                        'text' => $request['pertanyaan'][$key],
                        'jenis_id' => $request['jenis'],
                        'rec_usr' => \Auth::user()->id
                    ]);

                    foreach ($request['pilihan'] as $key_pil => $pil) {
                        $pilihan = \DB::table('pilihan')->insertGetId([
                            'text' => $request['pilihan'][$key_pil],
                            'pertanyaan_id' => $reusable,
                            'rec_usr' => \Auth::user()->id
                        ]);
                    }

                    \DB::table('pertanyaan_survey')->insert([
                        'survey_id' => $id,
                        'pertanyaan_id' => $reusable,
                        'rec_usr' => \Auth::user()->id
                    ]);

                    \DB::table('pilihan_multiple')->insert([
                        'pertanyaan_id' => $reusable,
                        'is_multiple' => $request['tipe_pilihan'][$key],
                    ]);
                }
                // dd('oke4');
                break;
            default:
                # code...
                break;
        }

        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
        ]);
    }

    public function saveInterviewer(Request $request){
        $this->validate($request,[
            'nama' => 'required|unique:interviewer,nama,NULL,id,deleted_at,NULL',
        ],[

        ]);

        try {
            $input = $request->all();

            Log::notice(' ======================== End Simpan Interviewer Reusable /interviewer ============================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ========================== Simpan Interviewer Reusable /interviewer ============================== ');

            $data = \DB::table('interviewer')->insert([
                'nama' => $input['nama'],
            ]);
            return response()->json([
                'message' => 'Sukses menyimpan data.',
                'status' => 'success',
                'status_code' => 200
             ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateInterviewer(Request $request){
        $id = \Crypt::decryptString($request->id);
        $this->validate($request,[
            'nama' => 'required|unique:interviewer,nama,'.$id.',id,deleted_at,NULL',
        ],[

        ]);

        try {
            $input = $request->all();

            Log::notice(' ======================== End Update Interviewer Reusable /interviewer ============================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ========================== Update Interviewer Reusable /interviewer ============================== ');

            $data = \DB::table('interviewer')->where('id',$id)->update([
                'nama' => $input['nama'],
            ]);
            return response()->json([
                'message' => 'Sukses menyimpan data.',
                'status' => 'success',
                'status_code' => 200
             ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteInterviewer(Request $request)
    {
        $id = \Crypt::decryptString($request->id);

        Log::notice(' ======================== End Delete Interviewer Reusable /interviewer ============================== ');
        Log::info('interviewer_id : '.$id);
        Log::info('user_id : '.$this->user_id);
        Log::notice(' ========================== Delete Interviewer Reusable /interviewer ============================== ');

        \DB::table('interviewer')->where('id',$id)->update([
            'deleted_at' => \Carbon\Carbon::now(),
        ]);
        return response()->json([
            'message' => 'Sukses menghapus data.',
            'status' => 'success',
            'status_code' => 200
         ]);
    }

    public function tambahUser(Request $request)
    {
        $this->validate($request,[
            'nama' => 'required',
            'username' => 'required|unique:users,nama,NULL,id',
            'password' => 'required|min:8',
            'conf_pass' => 'required|same:password',
        ],[
            'nama.required' => 'Nama User tidak boleh kosong',
            'username.required' => 'Nama User tidak boleh kosong',
            'username.unique' => 'Nama User sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'conf_pass.required' => 'Konfirmasi password tidak boleh kosong',
            'conf_pass.same' => 'Konfirmasi Password Tidak Sama Dengan Password',
        ]);

        try {
            $input = $request->all();
            Log::notice(' =============================== End tambah CRUD /data-user =================================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= tambah CRUD /data-user =================================== ');

            $user = New \App\User;
            $user->nama_lengkap = $request->nama;
            $user->nama = $request->username;
            $user->password = bcrypt($request->password);
            $user->status = 1;
            $success = $user->save();

            $role = \App\Models\Role::find($request->role);

            $user->attachRole($role);

            if ($success) {
                $message = 'Data berhasil ditambahkan';
            } else {
                $message = 'Data gagal ditambahkan';
            }

            return response()->json(['success'=>$success, 'message'=>$message]);
            
        } catch (Exception $e) {
            return response()->json(['success'=>false, 'message'=>$message]);
        }
    }

    public function editUser(Request $request)
    {
        $id = \Crypt::decryptString($request->id);

        $this->validate($request,[
            'nama' => 'required',
            'username' => 'required|unique:users,nama,'.$id.',id',
            'password' => 'nullable|min:8',
            'conf_pass' => 'same:password',
        ],[
            'nama.required' => 'Nama User tidak boleh kosong',
            'username.required' => 'Nama User tidak boleh kosong',
            'username.unique' => 'Nama User sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'conf_pass.same' => 'Konfirmasi Password Tidak Sama Dengan Password',
        ]);

        try {
            $input = $request->all();
            Log::notice(' =============================== End edit CRUD /data-user =================================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= edit CRUD /data-user =================================== ');

            $user = \App\User::find($id);
            $user->nama_lengkap = $request->nama;
            $user->nama = $request->username;
            $user->password = bcrypt($request->password);
            $user->status = 1;
            $success = $user->save();

            $role = \App\Models\Role::find($request->role);

            $user->roles()->sync($role);

            if ($success) {
                $message = 'Data berhasil diubah';
            } else {
                $message = 'Data gagal diubah';
            }

            return response()->json(['success'=>$success, 'message'=>$message]);
            
        } catch (Exception $e) {
            return response()->json(['success'=>false, 'message'=>$message]);
        }
    }

    public function statusUser(Request $request)
    {
        try {

            $id = \Crypt::decryptString($request->id);
            $input = $request->all();

            $user = \App\User::where('id',$id)->first();
            if ($request->status == 1) {
                $user->status = 0;
                $success = $user->save();
                if ($success) {
                    
                    Log::notice(' =============================== End Non-Aktif CRUD /data-user =================================== ');
                    Log::info(json_encode($input));
                    Log::info('user_id : '.$this->user_id);
                    Log::notice(' ================================= Non-Aktif CRUD /data-user =================================== ');

                    $message = 'User berhasil dinonaktifkan';
                }else{
                    $message = 'User gagal dinonaktifkan';
                }
            }else{
                $user->status = 1;
                $success = $user->save();
                if ($success) {
                    Log::notice(' =============================== End Aktif CRUD /data-user =================================== ');
                    Log::info(json_encode($input));
                    Log::info('user_id : '.$this->user_id);
                    Log::notice(' ================================= Aktif CRUD /data-user =================================== ');

                    $message = 'User berhasil diaktifkan';
                }else{
                    $message = 'User gagal diaktifkan';
                }
            }

            return response()->json(['success'=>$success, 'message'=>$message]);

        } catch (Exception $e) {
            return response()->json(['success'=>false, 'message'=>$message]);
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $id = \Crypt::decryptString($request->id);

            \DB::table('users')->where('id',$id)->delete();

            \DB::table('role_user')->where('user_id',$id)->delete();
            
            return response()->json([
                'message' => 'Sukses menghapus data.',
                'status' => 'success',
                'status_code' => 200
            ]);

        } catch (Exception $e) {
            return response()->json(['success'=>false, 'message'=>$e->getMessage()]);
        }
    }

    public function inputScoreSurvey($ids,Request $request){
        \DB::transaction(function () use ($request, $ids) {
            $id = Crypt::decryptString($ids);
            $input = $request->all();
            Log::notice(' =============================== End Save Survey =================================== ');
            Log::info(json_encode($input));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Save Survey =================================== ');

            $sequence = $request->get('sequence');

            $survey = \DB::select(\DB::raw('SELECT id, pertanyaan_id, survey_id, deleted_at 
                                            FROM pertanyaan_survey 
                                            WHERE survey_id = '.$id.' 
                                            AND deleted_at IS NULL'));
            DB::table('survey')->where('id',$id)->update([
                'skor' => 1
            ]);
            foreach ($sequence as $key => $seq) {
                foreach ($input['bobot_pilihan'][$seq] as $key_pil => $pil) {
                    $id_pilihan = $input['id_pilihan'][$seq][$key_pil];
                    \DB::table('pilihan')->where('id',$id_pilihan)->update([
                        'bobot' => $pil,
                        'updated_at' => Carbon::now(),
                        'deleted_at' => NULL,
                    ]);
                }
            }
        });
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
         ]);

    }

    public function saveKlasifikasi(Request $request){
        \DB::transaction(function () use ($request) {
            $id = Crypt::decryptString($request->ref);
            
            Log::notice(' =============================== End Save Survey =================================== ');
            Log::info(json_encode($request->all()));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Save Survey =================================== ');
            $cek = DB::table('klasifikasi_skor')->where('survey_id',$id)->where('deleted_at',NULL)->get(); 
            DB::table('survey')->where('id',$id)->update([
                'klasifikasi' => 1
            ]);
            if ($cek != NULL) {
                DB::table('klasifikasi_skor')->where('survey_id',$id)->update([
                    'deleted_at' => Carbon::now()
                ]);
            };
            foreach($request->klasifikasi AS $key => $value){
                if(isset($request->id_klas[$key])){
                    DB::table('klasifikasi_skor')->where('id',$request->id_klas[$key])->update([
                        'klasifikasi' => $value,
                        'logika' => $request->logika[$key],
                        'nilai_a' => $request->nilai_a[$key],
                        'nilai_b' => $request->nilai_b[$key],
                        'updated_at' => Carbon::now(),
                        'deleted_at' => NULL,
                    ]);
                }else{
                    DB::table('klasifikasi_skor')->insert([
                        'survey_id' => $id,
                        'klasifikasi' => $value,
                        'logika' => $request->logika[$key],
                        'nilai_a' => $request->nilai_a[$key],
                        'nilai_b' => $request->nilai_b[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        });
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
        ]);

    }

    public function saveLinked(Request $request){
        DB::transaction(function () use ($request) {
            $id = Crypt::decryptString($request->ref);
            
            Log::notice(' =============================== End Save Survey =================================== ');
            Log::info(json_encode($request->all()));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Save Survey =================================== ');
            $cek = DB::table('multisurvey')->where('interviewer_id',$id)->where('deleted_at',NULL)->get(); 
            if ($cek != NULL) {
                DB::table('multisurvey')->where('interviewer_id',$id)->update([
                    'deleted_at' => Carbon::now()
                ]);
            };
            if (isset($request->surveyreusable)) {
                foreach($request->surveyreusable AS $key => $value){
                    $cekmulti = DB::table('multisurvey')->where('interviewer_id',$id)->where('survey_id',$value)->first(); 
                    if($cekmulti != NULL){
                        DB::table('multisurvey')->where('interviewer_id',$id)->where('survey_id',$value)->update([
                            'updated_at' => Carbon::now(),
                            'deleted_at' => NULL,
                        ]);
                    }else{
                        DB::table('multisurvey')->insert([
                            'interviewer_id' => $id,
                            'survey_id' => $value,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
        });
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
        ]);
    }

    public function saveSoalResponden(Request $request){
        \DB::transaction(function () use ($request) {
            $id = Crypt::decryptString($request->ref);
            
            Log::notice(' =============================== End Save Survey =================================== ');
            Log::info(json_encode($request->all()));
            Log::info('user_id : '.$this->user_id);
            Log::notice(' ================================= Save Survey =================================== ');
            $cek = DB::table('soal_responden')->where('survey_id',$id)->where('deleted_at',NULL)->get(); 
            DB::table('survey')->where('id',$id)->update([
                'soal_resp' => 1
            ]);
            if ($cek != NULL) {
                DB::table('soal_responden')->where('survey_id',$id)->update([
                    'deleted_at' => Carbon::now()
                ]);
            };
            foreach($request->tipe AS $key => $value){
                if(isset($request->id_soal_resp[$key])){
                    DB::table('soal_responden')->where('id',$request->id_soal_resp[$key])->update([
                        'tipe' => $value,
                        'label' => $request->label[$key],
                        'is_required' => isset($request->is_required[$key]) ? $request->is_required[$key] :0,
                        'value' => isset($request->value[$key]) ? $request->value[$key] : '',
                        'updated_at' => Carbon::now(),
                        'deleted_at' => NULL
                    ]);
                }else{
                    DB::table('soal_responden')->insert([
                        'survey_id' => $id,
                        'tipe' => $value,
                        'label' => $request->label[$key],
                        'is_required' => isset($request->is_required[$key]) ? $request->is_required[$key] :0,
                        'value' => isset($request->value[$key]) ? $request->value[$key] : '',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        });
        return response()->json([
            'message' => 'Sukses menyimpan data.',
            'status' => 'success',
            'status_code' => 200
        ]);
    }
}
