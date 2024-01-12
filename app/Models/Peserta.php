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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the method to get the image URL
    public function getImageURL()
    {
        // Assuming 'foto' is the field in the users table that stores the image URL
        if ($this->user && $this->user->foto) {
            return $this->user->foto;
        } else {
            // If 'foto' is null or user is not found, return a default URL
            return "https://api.dicebear.com/6.x/fun-emoji/svg?seed={$this->name}";
        }
    }

}
