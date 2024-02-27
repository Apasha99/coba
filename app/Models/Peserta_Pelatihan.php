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
        'last_accessed'
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }
}
