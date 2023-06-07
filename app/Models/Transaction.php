<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'oeuvre',
        'user_name',
        'montant',
    ];



    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

}
