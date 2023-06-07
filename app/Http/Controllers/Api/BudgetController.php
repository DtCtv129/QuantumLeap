<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CaisseController;
use App\Models\Caisse;
use App\Models\Programme;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;

class BudgetController extends Controller
{
    use ApiHelpers;
    public function getBudget(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $budgets=Caisse::all();
            $programmes =Programme::all()->keyBy('id');
            // 
            
            $response=[
                "initialBudget"=> [
                    'title' => 'The initial amount',
                    'amount' => $budgets[1]->budget
                    ],
                "currentBudget"=>[
                    'title' => 'The current amount',
                    'amount' => $budgets[3]->budget
                    ],
                "expensesBudget"=>[
                    'title' => 'The expenses',
                    'amount' => $budgets[2]->budget
                    ],
                "blackBox"=>[
                    'title' => 'Black box',
                    'amount' => $budgets[1]->budget
                    ],
                "chaptersAmounts"=>$programmes
            ];
         return $this->onSuccess($response, 'Success');
         }
        return $this->onError(401,"Unauthorized Access");
    }
    public function initBudget(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $Caisse=Caisse::where('id',1)->first();
            $budgetCaisse=$Caisse->budget;
            $Caisse->update([
                "budget"=>$budgetCaisse+$request->recette
            ]);
            $Caisse=Caisse::where('id',1)->first();
            $budgetCaisse=$Caisse->budget;
            $Init=Caisse::where('id',2)->first()->update([
                "budget"=>$budgetCaisse
            ]);
            $expenses=Caisse::where('id',3)->first()->update([
                "budget"=>0
            ]);
            $expenses=Caisse::where('id',4)->first()->update([
                "budget"=>$budgetCaisse
            ]);
            Programme::query()
            ->update([
                'montant' => null,
            ]);
            $budgets=Caisse::all();
         return $this->onSuccess($budgets, 'Success');
         }
        return $this->onError(401,"Unauthorized Access");
    }
    
}
