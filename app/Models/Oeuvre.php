<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oeuvre extends Model
{
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'titre',
        'description',
        'nbr_doc'
    ];
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
