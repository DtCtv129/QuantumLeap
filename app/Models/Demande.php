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
        'status',
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
        return $this->belongsTo(Oeuvre::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function demande()
    {
        return $this->hasMany(Notification::class);
    }
}
