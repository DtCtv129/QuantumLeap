<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    use HasFactory;
    protected $fillable = [
        'demande_id',
        'name'
    ];
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
   
}
