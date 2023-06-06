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
    public function setUpCaisse(){
        $data = [
            ['id' => 1, 'budget' => 100000],
            ['id' => 2, 'budget' => 100000],
            ['id' => 3, 'budget' => 0],
            ['id' => 4, 'budget' => 100000]
        ];
        
        Caisse::insert($data);
    }
}
