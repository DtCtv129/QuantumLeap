<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Caisse;
use Validator;
use App\Http\Library\ApiHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgrammesController extends Controller
{
    use ApiHelpers;
    public function getProgrammes(Request $request):JsonResponse
    {
        $user = $request->user();

        if ($this->isAdmin($user)) {
            $programmes=Programme::with('oeuvres')->get();
            
         return $this->onSuccess($programmes, 'Success');
         }
        return $this->onError(401,"Unauthorized Access");
    }
    public function getProgrammesTitles(Request $request):JsonResponse
    {
        $user = $request->user();

        if ($this->isAdmin($user)) {
            $programmes=Programme::all()->pluck('titre');
         return $this->onSuccess($programmes, 'Success');
         }
        return $this->onError(401,"Unauthorized Access");
    }
    public function createProgramme(Request $request):JsonResponse
    {
       $user = $request->user();
        if ($this->isAdmin($user)) {
           $budget=Caisse::first()->budget;
           $validator = Validator::make($request->all(), $this->programmeValidatedRules());
           if ($validator->passes()) {
            if ($request->montant<=$budget) {
          
                $programme=Programme::create([
                    'montant' => $request->montant,
                    'titre' => $request->titre,
                ]);
                return $this->onSuccess($programme, 'Programme Created Successfully');
            }
                return $this->onError(405, "Budget insuffisant");
               }
           return $this->onError(400, $validator->errors());
        }

        return $this->onError(401,"Unauthorized Access");
    }
    public function updateProgramme(Request $request, $id):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
                $programme=Programme::where('id',$id)->first();
                $programme->update([
                    'titre' => $request->titre,
                ]);
                return $this->onSuccess($programme, 'Programme Updated Successfully');
        }

        return $this->onError(401,"Unauthorized Access");
    }
    public function deleteProgramme(Request $request, $id):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
                $programme=Programme::where('id',$id)->first();
                $programme->delete();
                return $this->onSuccess($programme, 'Programme Deleted Successfully');
        }
        
        return $this->onError(401,"Unauthorized Access");
    }



    public function transferer(Request $request)
{
    // Vérifier si l'utilisateur est un administrateur
    //$user = $request->user();
    if ($this->isAdmin($request->user())) {
        // Récupérer les programmes source et destination à partir des paramètres de la requête
        $source = Programme::where('titre', $request->input('source'))->firstOrFail();
        $destination = Programme::where('titre', $request->input('destination'))->firstOrFail();

        // Récupérer la valeur à transférer à partir des paramètres de la requête
        $valeur = intval($request->input('valeur'));

        // Vérifier si la valeur à transférer est supérieure au montant du programme source
        if ($source->montant < $valeur) {
            return response()->json(['message' => 'Erreur, the value is greater then the amount of programme'], 400);
        }

        // Mettre à jour les montants des programmes source et destination
        $source->montant -= $valeur;
        $destination->montant += $valeur;

        // Sauvegarder les changements dans la base de données
        $source->save();
        $destination->save();

        // Retourner une réponse JSON avec les programmes source et destination mis à jour
        return response()->json([
            'source' => $source,
            'destination' => $destination,
        ]);
    } else {
        return response()->json(['error' => 'Accès non autorisé.'], 401);
    }





}
}
