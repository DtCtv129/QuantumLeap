<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    protected $table = 'programmes';

    protected $fillable = [
        'montant',
        'titre',
    ];
    public function oeuvres()
    {
        return $this->hasMany(Oeuvre::class);
    }
}
