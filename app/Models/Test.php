<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'test';
    protected $fillable = [
        'nama',
        'totalnilai',
        'isActive',
        'plt_kode',
        'deskripsi',
        'acak_soal',
        'acak_jawaban',
        'tampil_hasil',
        'start_date',
        'end_date',
        'durasi'
    ];

    public function soal_test()
    {
        return $this->hasMany(Soal_Test::class, 'test_id', 'id')->orderBy('urutan');
    }

    public function jawaban_test()
    {
        return $this->hasMany(Jawaban_Test::class, 'test_id', 'id');
    }

    public function nilai_test()
    {
        return $this->hasMany(Nilai_Test::class, 'test_id', 'id');
    }
}
