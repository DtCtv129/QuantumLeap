<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Caisse;
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
    public function createProgramme(Request $request):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
           $budget=Caisse::first()->budget;
            if ($request->montant<=$budget) {
          
                $programme=Programme::create([
                    'montant' => $request->montant,
                    'titre' => $request->titre,
                ]);
                return $this->onSuccess($programme, 'Programme Created Successfully');
            }
            return $this->onError(405, "Budget insuffisant");
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
}
