<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal_Test extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'soal_test';
    protected $fillable = [
        'test_id',
        'title',
        'urutan',
        'nilai',
        'tipe',
        'file_soal',
        'tipe_nilai'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function jawaban_test()
    {
        return $this->hasMany(Jawaban_Test::class, 'soal_id', 'id')->orderBy('urutan');
    }

    public function nilai_test()
    {
        return $this->hasOne(Nilai_Test::class, 'soal_id', 'id');
    }
}
