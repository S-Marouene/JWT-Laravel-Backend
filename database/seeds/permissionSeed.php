<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class permissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        

        /* Permission::create(['name' => 'calendrier']);
        Permission::create(['name' => 'test_crud']);
        Permission::create(['name' => 'condidat']);
         */// create permissions
        /* Permission::create(['name' => 'modif']);
        Permission::create(['name' => 'supprimer']);
        Permission::create(['name' => 'read']);
 */
        // create roles and assign existing permissions
/*         $role1 = Role::create(['name' => 'super-admin']);
         $role1->givePermissionTo('modif');
        $role1->givePermissionTo('supprimer');
        $role1->givePermissionTo('read');
 */ 
       /*  $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('modif');
        $role2->givePermissionTo('read');

        $role3 = Role::create(['name' => 'user']);
        $role3->givePermissionTo('read');  */ 

        // gets all permissions via Gate::before rule; see AuthServiceProvider

       
/* 
        $superadmin = User::where('role', '=', 'super-admin')->first();

        $superadmin->assignRole(['name' => 'super-admin']); */

       /*  $admin = User::where('role', '=', 'admin')->first();

        $admin->assignRole(['name' => 'admin']);
        
        $user = User::where('role', '=', 'user')->first();

        $user->assignRole(['name' => 'user']);  */
    }
}
