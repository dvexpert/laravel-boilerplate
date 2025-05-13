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
            'first_name' => 'Teq',
            'last_name'  => 'Buddies',
            'email'      => 'info@teqbuddies.com',
        ]);

        $user->assignRole(Role::findByName(RoleEnum::SYSTEM_ADMINISTRATOR->value));
    }
}
