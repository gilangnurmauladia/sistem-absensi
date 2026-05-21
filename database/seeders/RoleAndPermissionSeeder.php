<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
 
class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
 
        // Create permissions
        $permissions = [
            'manage-users',
            'manage-roles',
            'manage-permissions',
            'manage-employees',
            'manage-attendance',
            'manage-performance',
            'view-dashboard',
        ];
 
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
 
        // Create roles and assign existing permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super admin gets all permissions (handled via Gate::before in AuthServiceProvider usually)
        
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo([
            'manage-employees',
            'manage-attendance',
            'manage-performance',
            'view-dashboard',
        ]);
 
        $karyawan = Role::firstOrCreate(['name' => 'Karyawan']);
        $karyawan->givePermissionTo([
            'view-dashboard',
        ]);
 
        // Assign Super Admin role to existing admin users
        $users = User::where('role', 'admin')->get();
        foreach ($users as $user) {
            $user->assignRole($superAdmin);
        }

        // Assign Karyawan role to existing karyawan users
        $karyawans = User::where('role', 'karyawan')->get();
        foreach ($karyawans as $user) {
            $user->assignRole($karyawan);
        }
    }
}
