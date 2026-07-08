<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@umkendari.ac.id'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        $role = Role::where('name', 'admin')->first();

        if ($role && !$admin->roles->contains($role->id)) {
            $admin->roles()->attach($role->id);
        }

        $this->command->info("Admin seeded: {$admin->email}");
    }
}
