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
                    'name' => $request->firstName." ".$request->lastName,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'job'=>$request->job,
                    'role' => 1
                ]);
                $userId = $user->id;
                return $this->onSuccess($userId, 'User Created Successfully');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401,"Unauthorized Access");
    }
    public function getUsers(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {          
                $users=User::all();
                return $this->onSuccess($users, 'Success');
             }
        return $this->onError(401,"Unauthorized Access");
    }
   

    public function loginUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'userEmail' => 'required|email',
            'password' => 'required',
        ]);
   
        if ($validator->passes()) {
            
            $user = User::where('email', $request->userEmail)->first();
            
            if (! $user || ! Hash::check($request->password, $user->password) ) {
                return $this->onError(401,"Wrong Email Or Password");
            }
             if((int)$user->role=== 0 ){
                    $userToken =  $user->createToken('auth-token', ['admin'])->plainTextToken; 
                    $role="admin";
                }   
                else{
                    $userToken =  $user->createToken('auth-token', ['user'])->plainTextToken;
                    $role="user";
                }
                 
                return $this->onSuccess(
                [
                "token"=>$userToken,
                "email"=>$user->email,
                "role"=>$role,
                "userName"=>$user->name
                ]
                , 'Sucess');
            } 
            return $this->onError(400, $validator->errors());
        }
       
    public function logoutUser(Request $request): JsonResponse
    {   
        
        $user = $request->user();
      if($user){
        $user->currentAccessToken()->delete();        
        return $this->onSuccess('','User Loged out successfully.');
            }   
         return $this->onError(401, "User Doesn't exist");
     }
     public function testLogin(Request $request): JsonResponse
     {   
         
         $user = $request->user();
         if($user){
        //  $user->currentAccessToken()->delete();        
         return $this->onSuccess('','Token recieved successfully');
             }   
             return $this->onError(401, "GG");
      }
    
    public function updateUser(Request $request, int $id):JsonResponse
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
                if(!empty($user) && $this->isAdmin($user)==false){
                    $user->delete();
                    return $this->onSuccess($user, 'User Deleted Successfully');
                }
                return $this->onError(404,"User Can't Be Deleted");
            }
        return $this->onError(401,"Unauthorized Access");
    }
    public function changePassword(request $request){
        $request->validate([
            'newPassword' => 'required', 
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->newPassword);
        $user->save();
        return response([
            'message' => 'Password changed Successfully',
            'status' => 'Success',

        ], 200);
    }
    public function userRole(Request $request):JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
                $role="admin";
                    return $this->onSuccess($role, '');
                }
        else if ($this->isUser($user)) {
                $role="user";
                return $this->onSuccess($role, '');
                }
        else
                return $this->onError(401,"User Doesn't exist");  
    }
    public function routeNotificationForDatabase()
{
    return $this->id;
}
public function setUpAdmin(Request $request): JsonResponse
{
    
     
            $user=User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'job' => $request->job,
                'role' => 0
            ]);
           

    return $this->onSuccess(200,"Admin Added Successfully");
}
}
