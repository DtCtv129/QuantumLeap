<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Auth;
use Validator;
use Illuminate\Http\JsonResponse;


class RoleController extends Controller
{


    function __construct()
    {
        $this->middleware('auth:api');

         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $roles = Role::orderBy('id', 'DESC')->paginate(5);

    return response()->json([
        'roles' => $roles,
        'currentPage' => $roles->currentPage(),
        'perPage' => $roles->perPage(),
        'total' => $roles->total()
    ], 200);
}


public function create()
{
    $permission = Permission::get();
    return response()->json(compact('permission'));
}


   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles,name',
        'permission' => 'required|array'
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 400);
    }
    
    $role = Role::create(['name' => $request->input('name')]);
    $role->syncPermissions($request->input('permission'));

    return response()->json([
        'message' => 'Role created successfully',
        'role' => $role
    ], 201);
}

   

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $role = Role::find($id);
    $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)
        ->get();
    
    return response()->json(['role' => $role, 'rolePermissions' => $rolePermissions]);
}

   

    /**
     * Update the specified resource in storage.
     */


     public function update(Request $request, $id)
{
    $this->validate($request, [
        'name' => 'required',
        'permission' => 'required',
    ]);

    $role = Role::find($id);
    $role->name = $request->input('name');
    $role->save();

    $role->syncPermissions($request->input('permission'));

    return response()->json([
        'message' => 'Role updated successfully',
        'data' => $role,
    ]);
}


public function edit($id)
{

    $role = Role::find($id);
    $permission = Permission::get();
    $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();

    return response()->json([
        'role' => $role,
        'permission' => $permission,
        'rolePermissions' => $rolePermissions,
    ]);
}

  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    Role::find($id)->delete();
    return response()->json([
        'message' => 'Role deleted successfully'
    ]);
}

   
}
