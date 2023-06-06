<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Oeuvre;
use App\Models\Caisse;
use App\Http\Library\ApiHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class OeuvresController extends Controller
{
    use ApiHelpers;
    public function getOeuvres(Request $request):JsonResponse
    {
        $user = $request->user();

        if ($this->isAdmin($user)) {
            $ouevre=Oeuvre::all();
            
         return $this->onSuccess($ouevre, 'Success');
         }
        return $this->onError(401,"Unauthorized Access");
    }
    public function createOeuvre(Request $request):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
       
                $oeuvre=Oeuvre::create([
                    'programme_id'=>$request->programmeId,
                    'titre'=>$request->titre,
                    'description'=>$request->description,
                    'amount'=>$request->amount
                ]);
                return $this->onSuccess($oeuvre, 'Oeuvre Created Successfully');
        }

        return $this->onError(401,"Unauthorized Access");
    }
    public function updateOeuvre(Request $request, $id):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
                $oeuvre=Oeuvre::where('id',$id)->first();
                $oeuvre->update([
                    'titre' => $request->titre,
                    'description'=>$request->description,
                    'amount'=>$request->amount
                ]);
                return $this->onSuccess($oeuvre, 'Oeuvre Updated Successfully');
        }

        return $this->onError(401,"Unauthorized Access");
    }
    public function deleteOeuvre(Request $request, $id):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
                $oeuvre=Oeuvre::where('id',$id)->first();
                $oeuvre->delete();
                return $this->onSuccess($oeuvre, 'Oeuvre Deleted Successfully');
        }
        
        return $this->onError(401,"Unauthorized Access");
    }
}
