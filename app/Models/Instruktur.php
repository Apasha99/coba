<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;
    protected $table = 'instruktur';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'bidang',
        'user_id'
    ];

    public function instruktur_pelatihan()
    {
        return $this->hasMany(Instruktur_Pelatihan::class, 'instruktur_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getImageURL()
    {
        if ($this->user && $this->user->foto) {
            return $this->user->foto;
        } else {
            return "https://api.dicebear.com/6.x/fun-emoji/svg?seed={$this->name}";
        }
    }
}
