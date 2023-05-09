<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Demande;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DemandesController extends Controller
{
    use ApiHelpers;

    public function getDemandes(Request $request):JsonResponse
    {
        $user = $request->user();

        if ($this->isAdmin($user)) {
            $demandes=Demande::with('piecejointes')->get();
         return $this->onSuccess($demandes, 'Success');
        }else{
            $demandes=Demande::with('piecejointes')->where('user_id',$user->id)->get();
         return $this->onSuccess($demandes, 'Success');
        }
    }
    public function createDemande(Request $request):JsonResponse
    {
            
            $user = $request->user();
            if (!$this->isAdmin($user)) {
                $validator = Validator::make($request->all(), $this->demandeValidatedRules());
                if ($validator->passes()) {
                    $demande=Demande::create([
                        'user_id'=>$request->user()->id,
                        'oeuvre_id'=>$request->oeuvreId,
                    ]);
                    // File Upload
                    $this->storeFiles($request,$demande->id);
                    return $this->onSuccess($demande, 'Demande Created Successfully');
                    }
                 return $this->onError(400, $validator->errors());
            }
            return $this->onError(401,"Unauthorized Access");
        }
        public function changeDemandeStatus(Request $request, int $id):JsonResponse
        {
            $user = $request->user();
            if ($this->isAdmin($user)) {
                    $demande=Demande::where('id',$id)->first();
                    if(!empty($demande)){
                        $demande->update([
                            'status' => $request->status
                        ]);
                        return $this->onSuccess($demande, 'Demande Status Updated Successfully');
                    }
                    return $this->onError(404,"Demande doesn't exist");
            }
    
            return $this->onError(401,"Unauthorized Access");
        }
        
}
