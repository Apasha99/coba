<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur_Pelatihan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'instruktur_pelatihan';
    protected $fillable = [
        'instruktur_id',
        'plt_kode',
    ];

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }
}
