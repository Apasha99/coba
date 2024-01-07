<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta_Pelatihan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'peserta_pelatihan';
    protected $fillable = [
        'peserta_id',
        'plt_kode',
    ];
}
