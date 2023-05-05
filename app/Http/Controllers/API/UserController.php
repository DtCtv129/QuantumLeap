<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Validator;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use App\Models\Profile;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function loginUser(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){

            return Response(['message' => $validator->errors()],401);
        }
   
        if(Auth::attempt($request->all())){

            $user = Auth::user(); 
    
            $success =  $user->createToken('MyApp')->plainTextToken; 
        
            return Response(['token' => $success],200);
        }

        return Response(['message' => 'email or password wrong'],401);
    }


    public function userDetails(): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user],200);
        }

        return Response(['data' => 'Unauthorized'],401);
    }


   
    public function logout(): Response
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
        
        return Response(['data' => 'User Logout successfully.'],200);
    }


    public function create()
{
    $roles = Role::pluck('name','name')->all();
    return response()->json(['roles' => $roles], 200);
}





public function store(Request $request):Response
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $profile = Profile::create([
            'user_id' => $user->id,
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'birthdate' => $request->input('birthdate')
        ]);
    
        return response()->json(['message' => 'User created successfully'], 201);
    
        
    }


    public function show($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    return response()->json(['user' => $user], 200);
}



public function edit($id)
{
    $user = User::find($id);
    $roles = Role::pluck('name','name')->all();
    $userRole = $user->roles->pluck('name','name')->all();

    return response()->json([
        'user' => $user,
        'roles' => $roles,
        'userRole' => $userRole
    ]);
}




   

public function update(Request $request, $id)
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$id,
        'password' => 'same:confirm-password',
        'roles' => 'required'
    ]);

    $input = $request->all();
    if(!empty($input['password'])){ 
        $input['password'] = Hash::make($input['password']);
    }else{
        unset($input['password']);
    }

    $user = User::find($id);
    $user->update($input);
    DB::table('model_has_roles')->where('model_id',$id)->delete();

    $user->assignRole($request->input('roles'));

    return response()->json([
        'message' => 'User updated successfully'
    ], 200);
}



 
    

        /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    User::find($id)->delete();
    return response()->json([
        'message' => 'Utilisateur supprimé avec succès'
    ], 200);
}

   
}
