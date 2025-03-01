<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(["name" => "admin", "guard_name" => "admin"]);
        $userRole = Role::create(["name" => "user", "guard_name" => "web"]);
        $permissions = [
            "create-task",
            "delete-task",
            "edit-task",
            "view-task",
            "reassign-task",
            "restore-task",
            "view-tasks",
            "restore-tasks",
            "delete-tasks",
            "change-progress",
        ];
        foreach ($permissions as $permission) {
            Permission::create(["name" => $permission, "guard_name" => 'admin']);
            if (in_array($permission, ["view-task", "change-progress"]))
                Permission::create(["name" => $permission, "guard_name" => 'web']);

        }
        $adminRole->givePermissionTo($permissions);
        $userRole->givePermissionTo(['view-task', 'change-progress']);

        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678910'),
        ]);

        $admin->assignRole('admin');
    }

}
