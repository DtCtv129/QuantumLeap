<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_demande',
        'url'
    ];
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
   
}
