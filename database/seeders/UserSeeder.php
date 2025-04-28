<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage-menu',
            'manage-users',
            'manage-pemesanan',

            // Kasir permissions
            'process-pembayaran'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $kasirRole = Role::create(['name' => 'kasir']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([
            'manage-menu',
            'manage-users',
            'manage-pemesanan'
        ]);

        $kasirRole->givePermissionTo([
            'process-pembayaran'
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('123456')
        ]);
        $admin->assignRole('admin');

        // Create kasir user
        $kasir = User::create([
            'name' => 'Kasir Resto',
            'email' => 'kasir@resto.com',
            'password' => bcrypt('kasir123')
        ]);
        $kasir->assignRole('kasir');
    }
}
