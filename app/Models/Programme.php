<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    protected $fillable = [
        'montant',
        'titre',
        'description'
    ];
    public function oeuvres(): HasMany
    {
        return $this->hasMany(Oeuvre::class);
    }
}
