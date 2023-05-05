<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  
    public function run()
    {
        //Permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete'
            
        ];
       
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
