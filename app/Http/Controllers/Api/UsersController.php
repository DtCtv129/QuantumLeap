<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    use ApiHelpers;
    
    public function createUser(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
          
                $user=User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 1
                ]);
                $userToken = $user->createToken('auth_token', ['user'])->plainTextToken;
                return $this->onSuccess($userToken, 'User Created Successfully');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401,"Unauthorized Access");
    }
   

    public function loginUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if ($validator->passes()) {
          
            $user = User::where('email', $request->email)->first();
 
            if (! $user || ! Hash::check($request->password, $user->password) ) {
                return $this->onError(401,"Wrong Email Or Password");
            }
             if((int)$user->role===0 ){
                    $userToken =  $user->createToken('auth-token', ['admin'])->plainTextToken; 
                }
                else{
                    $userToken =  $user->createToken('auth-token', ['user'])->plainTextToken;
                }
                 
                return $this->onSuccess($userToken, 'Sucess');
            } 
            return $this->onError(400, $validator->errors());
        }
       
    public function logoutUser(): JsonResponse
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
        
        return $this->onSuccess('','User Loged out successfully.');
    }
   
    public function updateUser(Request $request, string $id):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->userUpdateValidatedRules());
            if ($validator->passes()) {
          
                $user=User::where('id',$id)->first();
                if(!empty($user)){
                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                    ]);
                    return $this->onSuccess($user, 'User Updated Successfully');
                }
                return $this->onError(404,"User doesn't exist");
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401,"Unauthorized Access");
    }
    public function deleteUser(Request $request, string $id):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
          
                $user=User::where('id',$id)->first();
                if(!empty($user)){
                    $user->delete();
                    return $this->onSuccess($user, 'User Deleted Successfully');
                }
                return $this->onError(404,"User doesn't exist");
            }
        return $this->onError(401,"Unauthorized Access");
    }
    public function destroy(string $id)
    {
        //
    }


    public function changePassword(request $request){
        $request->validate([
            'password' => 'required|confirmed', 

        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return response([
            'message' => 'Password changed Successfully',
            'status' => 'Success',

        ], 200);





    }
}
