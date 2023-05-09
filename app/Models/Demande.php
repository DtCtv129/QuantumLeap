<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'oeuvre_id',
    ];
    public function piecejointes()
    {
        return $this->hasMany(PieceJointe::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function oeuvre()
    {
        return $this->BelogsTo(Oeuvre::class);
    }
}