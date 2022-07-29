<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TableController extends Controller
{
    /**
    * fungsi data master untuk menampilkan data peserta
    */
    public function tabelResponden(){

        $data = \DB::table('responden');

        return \DataTables::of($data)
        ->addColumn('action', function ($data) {
            return '<div class="btn-toolbar">
                            <div class="btn-group">
                                <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li onclick="deletedata(' . $data->id . ')"><a class="btn-trash"><i class="fa fa-trash " id="hapus"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </div>
                        ';
        })
        ->editColumn('nama', function ($data) {
            return $data->nama;
        })
        // ->filterColumn('nama',function($query, $keyword){
        //     return $query->whereRaw("CONCAT(nama) like ?", ["%{$keyword}%"]);
        // })
        ->editColumn('telepon', function ($data) {
            return $data->telepon;
        })
        ->editColumn('umur', function ($data) {
            return $data->umur;
        })
        ->editColumn('alamat', function ($data) {
            return $data->alamat;
        })
        ->rawColumns(['action'])->addIndexColumn()->make(true);
    }

    public function tabelSurvey(){
        $data = \DB::table('view_survey')->orderBy('id','DESC');

        return \DataTables::of($data)
        ->addColumn('action', function ($data) {
            $cek = \DB::table('pertanyaan_survey')->where('survey_id',$data->id)->first();
            if ($cek != null) {
                $html = '<div class="btn-toolbar">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                    <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="btn-trash" href="/edit_question/'.Crypt::encryptString($data->id).'"><i class="fa fa-plus " id="add"></i> Edit Pertanyaan</a></li>
                                        <li><a class="btn-soal-resp" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Identitas Respondent</a></li>
                                        <li class="divider"></li>
                                        <li><a href="/input-soal-web/'.Crypt::encryptString($data->id).'" target="_blank"><i class="fa fa-pencil"></i> Isi Survey</a></li>
                                        <li class="divider"></li>
                                        <li><a class="btn-trash" id="edit" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Edit Nama Project</a></li>
                                        <li><a class="btn-trash" id="copy" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-copy"></i> Copy Project</a></li>
                                    </ul>
                                </div>
                            </div>
                            ';
            }else{
                $html = '
                        <div class="btn-toolbar">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                    <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="btn-trash" href="/edit_question/'.Crypt::encryptString($data->id).'"><i class="fa fa-plus " id="add"></i> Tambah Pertanyaan</a></li>
                                        <li class="divider"></li>
                                        <li><a class="btn-trash" id="edit" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Edit Nama Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        ';
            }
            return $html;
        })
        ->editColumn('nama', function ($data) {
            return $data->nama;
        })
        ->editColumn('durasi', function ($data) {
            return $data->durasi;
        }) 
        ->editColumn('editable', function ($data) {
            if ($data->total_responden > 0) {
                return '<span class="label label-default"> Not-Editable </span>';
            }else{
                return '<span class="label label-primary"> Editable </span>';
            }
            // return $data->durasi;
        }) 
        ->editColumn('pertanyaan', function ($data) {
            // return $data->pertanyaan;
            $cek = \DB::table('pertanyaan_survey')->where('survey_id',$data->id)->first();
            if ($cek != null) {
                $button = '<a href="/edit_question/'.Crypt::encryptString($data->id).'" class="btn btn-success btn-sm btn-circle"><i class="fa fa-pencil"></i> Pertanyaan </a>';
            }else{
                $button = '<a href="/edit_question/'.Crypt::encryptString($data->id).'" class="btn btn-primary btn-sm btn-circle"><i class="fa fa-plus"></i> Pertanyaan </a>';
            }
            return $button;
        })
        ->editColumn('soal_resp', function ($data) {
            if ($data->soal_resp == 0) {
                return '<i class="fa fa-close fa-2x" style="color:red;"></i>';
            }else{
                return '<i class="fa fa-check fa-2x" style="color:green;"></i>';
            }
        }) 
        ->filterColumn('nama',function($query, $keyword){
            return $query->whereRaw("CONCAT(nama) like ?", ["%{$keyword}%"]);
        })

        ->rawColumns(['action','pertanyaan','editable','soal_resp'])->addIndexColumn()->make(true);

    }

    public function tabelSurveyReusable(){
        $data = \DB::table('view_survey_reusable')->orderBy('id','DESC');;
        // $id_enkrip = Crypt::encryptString($id);
        return \DataTables::of($data)
        ->addColumn('action', function ($data) {

                $cek = \DB::table('pertanyaan_survey')->where('survey_id',$data->id)->first();
                if ($cek != NULL) {
                    $html = '<div class="btn-toolbar">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                        <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                        <li><a class="btn-soal-resp" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Identitas Respondent</a></li>
                                            <li><a class="btn-trash" href="/input-score-survey/'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Input Skor</a></li>
                                            <li><a class="btn-mdl" data-type="klasifikasi-skor" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Input Klasifikasi Skor</a></li>
                                            <li class="divider"></li>
                                            <li><a class="btn-trash" id="edit" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Edit Nama Project</a></li>
                                            <li><a class="btn-trash" id="copy" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-copy"></i> Copy Project</a></li>
                                        </ul>
                                    </div>
                                </div>
                            ';
                }else{
                    $html = '<div class="btn-toolbar">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                        <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="btn-trash" id="edit" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Edit Nama Project</a></li>
                                        </ul>
                                    </div>
                                </div>
                            ';
                }
            return $html;
        })
        ->editColumn('nama', function ($data) {
            return $data->nama;
        })
        ->editColumn('durasi', function ($data) {
            return $data->durasi;
        }) 
        ->editColumn('soal_resp', function ($data) {
            if ($data->soal_resp == 0) {
                return '<i class="fa fa-close fa-2x" style="color:red;"></i>';
            }else{
                return '<i class="fa fa-check fa-2x" style="color:green;"></i>';
            }
        }) 
        ->editColumn('editable', function ($data) {
            if ($data->total_responden > 0) {
                return '<span class="label label-default"> Not-Editable </span>';
            }else{
                return '<span class="label label-primary"> Editable </span>';
            }
        }) 
        ->editColumn('skor', function ($data) {
            if ($data->skor == 0) {
                return '<i class="fa fa-close fa-2x" style="color:red;"></i>';
            }else{
                return '<i class="fa fa-check fa-2x" style="color:green;"></i>';
            }
        }) 
        ->editColumn('klasifikasi', function ($data) {
            if ($data->klasifikasi == 0) {
                return '<i class="fa fa-close fa-2x" style="color:red;"></i>';
            }else{
                return '<i class="fa fa-check fa-2x" style="color:green;"></i>';
            }
        }) 
        ->editColumn('pertanyaan', function ($data) {
            // return $data->pertanyaan;
            $cek = \DB::table('pertanyaan_survey')->where('survey_id',$data->id)->first();
            if ($cek != null) {
                $button = '<a href="/edit_question/'.Crypt::encryptString($data->id).'" class="btn btn-success btn-sm btn-circle"><i class="fa fa-pencil"></i> Pertanyaan </a>';
            }else{
                $button = '<a href="/edit_question/'.Crypt::encryptString($data->id).'" class="btn btn-primary btn-sm btn-circle"><i class="fa fa-plus"></i> Pertanyaan </a>';
            }
            return $button;
        })
        ->filterColumn('nama',function($query, $keyword){
            return $query->whereRaw("CONCAT(nama) like ?", ["%{$keyword}%"]);
        })

        ->rawColumns(['action','pertanyaan','editable','skor','klasifikasi','soal_resp'])->addIndexColumn()->make(true);

    }


    public function tabelDevices(){
        $data = \DB::table('device')->where('deleted_at',null);

        return \DataTables::of($data)
        ->addColumn('action', function ($data) {
                $html = '
                        <div class="btn-toolbar">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                    <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                    '.($data->survey_id != null ? '<li><a class="btn-trash" href="javascript:;" data-id="'.$data->survey_id.'" id="hapus_link"><i class="fa fa-unlink "></i> Hapus Link Survey</a></li>
                                        <li class="divider"></li>' : '').'
                                        
                                        <li><a class="btn-pencil" href="javascript:;" data-id="'.$data->id.'" id="edit_devices"><i class="fa fa-pencil " ></i> Edit Devices</a></li>
                                        <li><a class="btn-trash" id="hapus_devices" data-id="'.$data->id.'"><i class="fa fa-trash " ></i> Hapus Devices</a></li>
                                    </ul>
                                </div>
                            </div>
                        ';
            return $html;
        })
        ->editColumn('nama', function ($data) {
            return $data->nama;
        })
        ->editColumn('keterangan', function ($data) {
            return $data->keterangan;
        }) 
        ->editColumn('survey', function ($data) {
            if ($data->survey_id != null) {
                return \Iff::namaSurvey2($data->survey_id);
            }else{
                return '';
            }
        }) 
        ->editColumn('status', function ($data) {
            // return $data->status;
            return '<span class="label label-default"> Not-Connected </span>';
        })
        ->filterColumn('nama',function($query, $keyword){
            return $query->whereRaw("CONCAT(nama) like ?", ["%{$keyword}%"]);
        })

        ->rawColumns(['action','status','nama'])->addIndexColumn()->make(true);

    }

    public function tabelReusable(){
        $data = \DB::table('survey')->where('deleted_at',NULL)->where('is_reusable',1);
        // $id_enkrip = Crypt::encryptString($id);

        return \DataTables::of($data)
        ->addColumn('action', function ($data) {
            $cek = \DB::table('pertanyaan_survey')->where('survey_id',Crypt::encryptString($data->id))->first();
            if ($cek != null) {
                $html = '<div class="btn-toolbar">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                    <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="btn-trash" href="/reusable/'.Crypt::encryptString($data->id).'"><i class="fa fa-plus " id="add"></i> Edit Pertanyaan</a></li>
                                        <li class="divider"></li>

                                        <li><a class="btn-trash" id="edit" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Edit Nama Project</a></li>
                                    </ul>
                                </div>
                            </div>
                            ';
            }else{
                $html = '
                        <div class="btn-toolbar">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                    <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="btn-trash" href="/reusable/'.Crypt::encryptString($data->id).'"><i class="fa fa-plus " id="add"></i> Tambah Pertanyaan</a></li>
                                        <li class="divider"></li>
                                        <li><a class="btn-trash" id="edit" data-id="'.Crypt::encryptString($data->id).'"><i class="fa fa-pencil"></i> Edit Nama Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        ';
            }
            return $html;
        })
        ->editColumn('nama', function ($data) {
            return $data->nama;
        })
        ->editColumn('durasi', function ($data) {
            return $data->durasi;
        }) 
        ->editColumn('pertanyaan', function ($data) {
            // return $data->pertanyaan;
            $cek = \DB::table('pertanyaan_survey')->where('survey_id',$data->id)->first();
            if ($cek != null) {
                $button = '<a href="/reusable/'.Crypt::encryptString($data->id).'" class="btn btn-success btn-sm btn-circle"><i class="fa fa-pencil"></i> Pertanyaan </a>';
            }else{
                $button = '<a href="/reusable/'.Crypt::encryptString($data->id).'" class="btn btn-primary btn-sm btn-circle"><i class="fa fa-plus"></i> Pertanyaan </a>';
            }
            return $button;
        })
        ->filterColumn('nama',function($query, $keyword){
            return $query->whereRaw("CONCAT(nama) like ?", ["%{$keyword}%"]);
        })

        ->rawColumns(['action','pertanyaan'])->addIndexColumn()->make(true);

    }

    public function tabelInterviewer()
    {
        $interviewer = \DB::table('interviewer')->where('deleted_at',null);
        
        return \DataTables::of($interviewer)  
            ->addColumn('action', function ($interviewer) {
                return '<div class="btn-toolbar">
                            <div class="btn-group">
                                <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                                <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a data-id="' . \Crypt::encryptString($interviewer->id) . '" id="btn-edit-interviewer"><i class="fa fa-pencil"></i> Edit Interviewer</a></li>
                                    <li><a data-id="' . \Crypt::encryptString($interviewer->id) . '" id="btn-delete-interviewer" jenis="interviewer"><i class="fa fa-trash-o delete-tag"></i> Hapus Interviewer</a></li>
                                    <li><a class="btn-mdl" data-type="linked" data-ref="'.Crypt::encryptString($interviewer->id).'"><i class="fa fa-link" aria-hidden="true"></i> Pilih Survey</a></li>
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['action'])->addIndexColumn()->make(true);
    }

    public function tabelSurveyJawaban(){
        $data = \DB::select(\DB::raw("SELECT s.id, s.nama, COUNT(r.survey_id) AS total_responden
        FROM survey AS s
        LEFT JOIN responden AS r ON r.survey_id = s.id
        WHERE s.deleted_at IS NULL
        GROUP BY s.id, s.nama
        HAVING (total_responden > 0)"));
        return \DataTables::of($data)
        ->addColumn('detail', function ($data) {
                $html = '
                        <a href="/responden/'.$data->id.'" title="Detail" class="btn btn-primary btn-sm"><i class="fa fa-sm fa-search"></i></a>
                        <a  class="btn btn-success btn-sm" title="Export Ke Excel" href="'.url('/answer/export_excel').'/'.$data->id.'" target="_blank" ><i class="fa fa-sm fa-file-excel-o"></i></a>
                        ';
            return $html;
        })
        ->rawColumns(['detail'])
        ->addIndexColumn()->make(true);
    }

    public function tabelSurveyResponden($id){
        $data = \DB::select("SELECT r.id, r.survey_id, r.waktu_mulai, r.waktu_selesai
        FROM responden r
        WHERE r.survey_id = $id AND r.deleted_at IS NULL");
        return \DataTables::of($data)
        ->editColumn('action', function ($data) {
          return  '<button class="btn btn-primary btn-xs details" data-ref="'.$data->id.'"><i class="fa fa-chevron-down"></i></button>';
        })->rawColumns(['action'])
        ->addIndexColumn()->make(true);
    }

    public function tabelSurveyRespondenDetail($id){
        $data = \DB::select("SELECT r.survey_id, sr.label, sr.tipe, jsr.value
        FROM responden r
        LEFT JOIN jawaban_soal_responden jsr ON jsr.responden_id = r.id
        LEFT JOIN soal_responden sr ON sr.id = jsr.soal_resp_id
        WHERE r.id = $id AND r.deleted_at IS NULL");
        return \DataTables::of($data)
        ->editColumn('value', function ($data) {
            if ($data->tipe == 'foto') {
                return  '<img src="/file-image/'.$data->value.'" style="max-width: 300px;max-height: 300px;">';
            }else{
                return $data->value;
            }
        })->rawColumns(['value'])
        ->addIndexColumn()->make(true);
    }

    public function tableUser()
    {
        $user  = \DB::table('view_user')->where('user_id',\Auth::user()->id)->first();
        
        switch ($user->role_id) {
            case '1':
                $user = \DB::table('view_user')->where('role_id','!=',1)->get();
                break;

            case '2':
                $user = \DB::table('view_user')->where([['role_id','!=',1],['role_id','!=',2]])->get();
                break;
            
            default:
                # code...
                break;
        }
        return \DataTables::of($user)
        ->addColumn('action', function($user){
            return '<div class="btn-toolbar">
                        <div class="btn-group">
                            <a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
                            <i class="fa fa-cog"> &nbsp; <i class="fa fa-caret-down"></i></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a data-id="' . \Crypt::encryptString($user->user_id) . '" id="btn-edit-user"><i class="fa fa-pencil " id="edit"></i> Edit </a></li>
                                <li><a data-id="' . \Crypt::encryptString($user->user_id) . '" id="btn-hapus-user" style=""><i class="fa fa-trash"></i> Hapus </a></li>
                            </ul>
                        </div>
                    </div>';
        })
        ->editColumn('status',function($user){
            if ($user->status == 1) {
                return '<a class="btn btn-primary"  id="sta_user" style="width:50%;height:60%;" status="'.$user->status.'" data-id="'.\Crypt::encryptString($user->user_id).'">Aktif</a>';
            }else{
                return '<a class="btn btn-danger" id="sta_user" style="width:50%;height:60%;" status="'.$user->status.'" data-id="'.\Crypt::encryptString($user->user_id).'">Nonaktif</a>';
            }
        })->rawColumns(['action','status'])->addIndexColumn()->make(true);
    }

}
