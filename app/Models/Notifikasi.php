<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'judul',
        'peserta_id',
        'isChecked',
        'plt_kode',
    ];
}
