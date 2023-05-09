<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Library\ApiHelpers;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;



class ProfileController extends Controller
{





    public function updateProfile(Request $request, $id) : JasonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return $this->onError(404,"User Not ....");

        }

        $user->job = $request->input('job');
        $user->salaire = $request->input('salaire');
        $user->Tel = $request->input('Tel');

        $user->save();
        return $this->onSuccess($user, 'Informations Updated Successfully');



        
    }
}
