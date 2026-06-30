<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal — orquestra a ordem de execução dos demais seeders.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PastaOptionSeeder::class,
            TableSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
