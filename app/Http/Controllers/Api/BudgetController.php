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
    // public function getProgrammesBudget(Request $request)
    // {
    //     $user = $request->user();
    //     if ($this->isAdmin($user)) {
    //         $budgets=Programme::all();
    //         foreach($budgets as $budget){
    //             $response[]=
    //         }
    //         $response=[
    //             "initialBudget"=>$budgets[1]->budget,
    //             "currentBudget"=>$budgets[3]->budget,
    //             "expensesBudget"=>$budgets[2]->budget,
    //             "blackBox"=>$budgets[0]->budget
    //         ];
    //      return $this->onSuccess($budgets, 'Success');
    //      }
    //     return $this->onError(401,"Unauthorized Access");
    // }
    
}
