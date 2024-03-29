<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban_User_Pilgan extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'jawaban_user_pilgan';
    protected $fillable = [
        'peserta_id',
        'test_id',
        'soal_id',
        'jawaban_id',
        'jawaban',
        'attempt'
    ];
}
