<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;
    protected $table = 'tugas';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'judul',
        'start_date',
        'end_date',
        'plt_kode',
        'deskripsi',
        'file_tugas',
        'nama_file'
    ];
}
