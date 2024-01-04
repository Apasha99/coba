<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai_Test extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'nilai_test';
    protected $fillable = [
        'peserta_id',
        'test_id',
        'soal_id',
        'jawaban_id',
        'nilai'
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id', 'id');
    }

}
