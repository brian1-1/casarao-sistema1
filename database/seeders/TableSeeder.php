<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

/**
 * Cria 12 mesas, todas inicialmente livres.
 */
class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Table::updateOrCreate(
                ['number' => $i],
                ['status' => 'livre']
            );
        }
    }
}
