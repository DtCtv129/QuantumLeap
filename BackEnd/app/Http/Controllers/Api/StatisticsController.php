<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Caisse;
use App\Models\Demande;
use App\Http\Library\ApiHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{   
    use ApiHelpers;
    public function getStats(Request $request){
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $stats=[
                "demandes"=>[
                    "pending"=>Demande::where('status','pending')->count(),
                    "rejected"=>Demande::where('status','rejected')->count(),
                    "approved"=>Demande::where('status','approved')->count(),
                    "payed"=>Demande::where('status','payed')->count()
                ]
            ];
            return $this->onSuccess($stats,"Success");
        }
        return $this->onError(401,"Unauthorized Access");
    }
}
