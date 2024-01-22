<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    use HasFactory;
    protected $fillable = ['submission_id', 'nama_file', 'path_file'];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }
}
