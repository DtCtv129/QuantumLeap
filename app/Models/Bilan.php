<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilan extends Model
{
    use HasFactory;

    protected $table = 'bilan';


    protected $fillable = [
        'budget_actuel',
        'titre_programme',
        'transaction_value',
        
        'budget_apres_trans',
        'type',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
