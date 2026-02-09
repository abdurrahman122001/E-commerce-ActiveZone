<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VendorRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'Vendor']);
        // Add permissions here if needed
        // $permission = Permission::firstOrCreate(['name' => 'manage vendor']);
        // $role->givePermissionTo($permission);
    }
}
