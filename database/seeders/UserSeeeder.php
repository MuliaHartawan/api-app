<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);
        $user = User::create(
        [
            'name' => "user",
            'email' => 'user@gmail.com',
            'password' => Hash::make('user'),
        ]);

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);

        $permissions = Permission::pluck('id','id')->all();

        $roleAdmin->syncPermissions($permissions);
        $roleUser->syncPermissions($permissions);

        $admin->assignRole([$admin->id]);
        $user->assignRole([$user->id]);
    }
}
