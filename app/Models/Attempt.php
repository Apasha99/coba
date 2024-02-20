<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'attempt';
    protected $fillable = [
        'peserta_id',
        'test_id',
        'totalnilai',
        'attempt',
    ];
}
