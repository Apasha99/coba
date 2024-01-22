<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = ['peserta_id', 'tugas_id', 'position', 'status', 'grading_status', 'nilai'];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
