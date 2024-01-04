<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;
    protected $table = 'pelatihan';
    protected $primaryKey = 'kode';
    protected $fillable = [
        'nama',
        'penyelenggara',
        'status',
        'start_date',
        'end_date',
        'tempat',
        'deskripsi'
    ];
}
