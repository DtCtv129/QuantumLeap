<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Models\Demande;
use Illuminate\Support\Facades\Storage;

class PieceJointesController extends Controller
{
    use ApiHelpers;
    
    public function getAttachments(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $attachments=Demande::where('id',$request->demandeId)
            ->with('piecejointes')
            ->first()["piecejointes"]
            ->pluck('name');
            return $this->onSuccess($attachments, 'Success');
        } 
        return $this->onError(401,"Unauthorized Access");
        
    }
    public function getFile(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $filePath='files/'.$request->name;
          return response()->file(Storage::path($filePath));
        } 
        return $this->onError(401,"Unauthorized Access");
        
    }
}
