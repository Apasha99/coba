<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $table = 'materi';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'judul',
        'file_materi',
        'plt_kode',
    ];
}
