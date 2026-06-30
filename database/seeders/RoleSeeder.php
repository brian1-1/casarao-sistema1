<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Cria os 4 perfis de acesso do sistema.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['slug' => Role::CLIENTE, 'name' => 'Cliente'],
            ['slug' => Role::GARCOM,  'name' => 'Garçom'],
            ['slug' => Role::COZINHA, 'name' => 'Cozinha'],
            ['slug' => Role::GERENTE, 'name' => 'Gerente'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
