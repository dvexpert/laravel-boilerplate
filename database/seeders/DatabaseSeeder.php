<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\{Role, User};
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolePermissionSeeder::class,
        ]);

        $user = User::factory()->create([
            'first_name' => 'PWD',
            'last_name'  => 'System Admin',
            'email'      => 'ppa-pwd-system-admin@example.com',
        ]);

        $user->assignRole(Role::findByName(RoleEnum::SYSTEM_ADMINISTRATOR->value));
    }
}
