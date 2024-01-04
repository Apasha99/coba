<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Peserta extends Model
{
    use HasFactory;
    protected $table = 'peserta';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'email',
        'noHP',
        'alamat'
    ];

    public function peserta_pelatihan()
    {
        return $this->hasMany(Peserta_Pelatihan::class, 'peserta_id', 'id');
    }

}
