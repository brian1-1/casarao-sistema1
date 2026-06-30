<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Cria um usuário de exemplo para cada perfil.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'slug');

        $users = [
            ['name' => 'Gerente',  'email' => 'gerente@casarao.com', 'role' => Role::GERENTE],
            ['name' => 'Garçom',   'email' => 'garcom@casarao.com',  'role' => Role::GARCOM],
            ['name' => 'Cozinha',  'email' => 'cozinha@casarao.com', 'role' => Role::COZINHA],
            ['name' => 'Cliente',  'email' => 'cliente@casarao.com', 'role' => Role::CLIENTE],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'password' => Hash::make('senha123'), // senha padrão de exemplo
                    'role_id'  => $roles[$u['role']],
                    'active'   => true,
                ]
            );
        }
    }
}
