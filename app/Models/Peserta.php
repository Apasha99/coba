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
        'alamat',
        'user_id'
    ];

    public function peserta_pelatihan()
    {
        return $this->hasMany(Peserta_Pelatihan::class, 'peserta_id', 'id');
    }

    public function submission()
    {
        return $this->hasMany(Submission::class, 'peserta_id', 'id');
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
