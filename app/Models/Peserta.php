<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Peserta extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'peserta';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'email',
        'noHP',
        'alamat'
    ];
}
