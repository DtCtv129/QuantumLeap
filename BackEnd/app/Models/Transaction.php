<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'montant',
    ];



    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

}
