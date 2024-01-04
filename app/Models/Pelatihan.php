<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;
    protected $table = 'pelatihan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode',
        'nama',
        'penyelenggara',
        'status',
        'start_date',
        'end_date',
        'tempat',
        'deskripsi',
        'poster'
    ];

    public function peserta_pelatihan()
    {
        return $this->hasMany(Peserta_Pelatihan::class, 'plt_kode', 'kode');
    }

    public function getPosterURL(){
        if($this->poster){
            return url("storage/" . $this->poster);
        }
        return "https://upload.wikimedia.org/wikipedia/commons/f/f2/Lambang_Kota_Semarang.png";
    }
}
