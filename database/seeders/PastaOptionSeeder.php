<?php

namespace Database\Seeders;

use App\Models\PastaOption;
use Illuminate\Database\Seeder;

/**
 * Opções do "Monte sua Massa": tipos de massa, molhos e ingredientes adicionais.
 */
class PastaOptionSeeder extends Seeder
{
    public function run(): void
    {
        $massas = ['Espaguete', 'Talharim', 'Penne', 'Fusilli', 'Nhoque'];

        foreach ($massas as $i => $name) {
            PastaOption::updateOrCreate(
                ['type' => PastaOption::MASSA, 'name' => $name],
                ['extra_price' => 0, 'active' => true, 'sort_order' => $i]
            );
        }

        $molhos = ['Sugo (tomate)', 'Branco (bechamel)', 'À bolonhesa', 'Alho e óleo', 'Pesto'];

        foreach ($molhos as $i => $name) {
            PastaOption::updateOrCreate(
                ['type' => PastaOption::MOLHO, 'name' => $name],
                ['extra_price' => 0, 'active' => true, 'sort_order' => $i]
            );
        }

        // Ingredientes com preço adicional (0 para os mais simples, valores pequenos para os especiais)
        $ingredientes = [
            ['Bacon', 4.00],
            ['Parmesão ralado', 0.00],
            ['Champignon', 3.50],
            ['Brócolis', 0.00],
            ['Frango desfiado', 5.00],
            ['Calabresa', 4.00],
            ['Tomate seco', 3.00],
            ['Azeitona', 0.00],
            ['Manjericão fresco', 0.00],
            ['Palmito', 4.50],
            ['Camarão', 9.00],
            ['Pimenta calabresa', 0.00],
        ];

        foreach ($ingredientes as $i => [$name, $price]) {
            PastaOption::updateOrCreate(
                ['type' => PastaOption::INGREDIENTE, 'name' => $name],
                ['extra_price' => $price, 'active' => true, 'sort_order' => $i]
            );
        }
    }
}
