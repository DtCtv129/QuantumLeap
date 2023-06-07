<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\User;
use App\Models\Caisse;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DemandeStatusNotification;
class DemandesController extends Controller
{
    use ApiHelpers;

    public function getDemandes(Request $request):JsonResponse
    {
        $user = $request->user();

        if ($this->isAdmin($user)) {
            $demandes=Demande::with('piecejointes')->with('user')->with('oeuvre')->get();
         return $this->onSuccess($demandes, 'Success');
        }else{
            $demandes=Demande::with('piecejointes')->where('user_id',$user->id)->get();
         return $this->onSuccess($demandes, 'Success');
        }
    }
    
    public function getDemandesStatus(Request $request):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $demandes=Demande::where('status',$request->status)->get();
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


        public function payDemande(Request $request, int $id ):JsonResponse
        {
            $user = $request->user();
            if ($this->isAdmin($user)) {
                //$demandesValides = Demande::where('status', 'valide')->first();
                $demandesValides=Demande::where('id',$id)->where('status', 'approved')->first();//stauts updatae
                if (!$demandesValides ) {
                    return $this->OnError(403, "This Request can't be payed");
                }else{
                                
                               
                                $programme = $demandesValides->oeuvre->programme;
                               // $demande = Demande::where('id', $id)->with('oeuvre.programme')->whereHas('oeuvre.programme')->first();

                                if (!$programme) {
                                    return $this->OnError(403, "Le programme correspondant n'a pas été trouvé");
                                }
                        
                                if ($programme->montant < $request->montant) {
                                    return $this->onError(403, "Le montant de la demande dépasse le montant du programme");
                                }
                                $transaction = Transaction::create([
                                    'oeuvre'=>$demandesValides->oeuvre->titre,
                                    'user_name'=>$demandesValides->user->name,
                                    'montant'=>$request->montant,
                                    "method"=>$request->method
                                ]); 
                                $programme->decrement('montant', $request->montant);                                                       
                                Caisse::where('id',1)->first()->decrement("budget",$request->montant);
                                
                                $demandesValides->status = 'payed';
                                $demandesValides->save();
                                $user = $demandesValides->user;
                                if ($user) {
                                    
                                    // Notification::send($user,new DemandeStatusNotification($demandesValides));
                                    // // User::where('id',$user->id)->first()->notify($notification);
                                }
                                  
                                
                                return $this->OnSuccess($transaction, "Transaction Success");                 
                }
                        
            }else {


                return $this-> onError(401,"Unauthorized Access");
                }

        }           
        
        
}
