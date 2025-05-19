<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use App\Models\{Permission, Role};
use Illuminate\Support\Facades\Schema;
use App\Enums\{PermissionEnum, RoleEnum};

class RolePermissionSeeder extends Seeder
{
    private string $now = '';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->now = now()->toDateTimeString();

        $this->seedRoles();
        $this->seedPermissions();
    }

    private function seedRoles(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            Role::query()->truncate();
        });

        Role::query()->insert(Arr::map(
            RoleEnum::cases(),
            fn ($enum): array => [
                'name'       => $enum->value,
                'guard_name' => 'web',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]
        ));
    }

    private function seedPermissions(): void
    {
        if (PermissionEnum::cases() === []) {
            $this->command->alert('No permissions defined yet...');

            return;
        }

        Schema::withoutForeignKeyConstraints(function (): void {
            Permission::query()->truncate();
        });

        Permission::query()->insert(Arr::map(
            PermissionEnum::cases(),
            fn ($enum): array => [
                'name'       => $enum->value,
                'guard_name' => 'web',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]
        ));
    }
}
