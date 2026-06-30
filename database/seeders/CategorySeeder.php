<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Categorias do cardápio (baseadas no projeto original "O Casarão").
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'pratos',  'name' => 'Pratos do Dia',    'icon' => 'ti-tools-kitchen-2', 'sort_order' => 1],
            ['slug' => 'espeto',  'name' => 'Espetinhos',        'icon' => 'ti-meat',            'sort_order' => 2],
            ['slug' => 'sides',   'name' => 'Acompanhamentos',   'icon' => 'ti-salad',           'sort_order' => 3],
            ['slug' => 'drinks',  'name' => 'Bebidas',           'icon' => 'ti-beer',            'sort_order' => 4],
            ['slug' => 'doces',   'name' => 'Sobremesas',        'icon' => 'ti-cookie',          'sort_order' => 5],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], $c + ['active' => true]);
        }
    }
}
