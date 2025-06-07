<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate([
            'phone' => '09120000000'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('supersecure123'),
            'email' => 'superadmin@gmail.com'
        ]);

        $role = Role::where('name', 'super_admin')->first();
        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
