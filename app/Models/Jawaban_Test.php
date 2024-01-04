<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban_Test extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'jawaban_test';
    protected $fillable = [
        'test_id',
        'soal_id',
        'title',
        'urutan',
        'status'
    ];

    public function nilai_test()
    {
        return $this->hasOne(Nilai_Test::class, 'jawaban_id', 'id');
    }
}
