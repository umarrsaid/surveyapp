<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;

class Jawaban extends Model
{
    protected $table = "view_responden";
 
    protected $fillable = ['survey_id','responden_id','nama', 'telepon', 'umur', 'alamat', 'email'];

    
}
