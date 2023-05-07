<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Caisse;
use Illuminate\Http\Request;

class CaisseController extends Controller
{
    public function add(int $montant){
        $caisse=Caisse::first();
        $caisse->update([
            'budget'=> $caisse->budget+$montant
        ]);
    }

    public function minus(int $montant){
        Caisse::first()->update([
            'budget'=> $caisse->budget-$montant
        ]);
    }
}
