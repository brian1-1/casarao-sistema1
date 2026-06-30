<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Produtos do cardápio — dados migrados do projeto PHP/React original.
 * Cada item: [nome, descrição, "serve X pessoas", preço, imagem, customizável].
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $u = 'https://images.unsplash.com/';

        $catalog = [
            'pratos' => [
                ['Feijoada Completa', 'Arroz, farofa, couve mineira e laranja.', 'Serve 1 pessoa', 48.90, $u.'photo-1556909114-f6e7ad7d3136?w=400&h=240&fit=crop', false],
                ['Frango com Quiabo', 'Acompanha polenta cremosa e arroz branco fresquinho.', 'Serve 1 pessoa', 42.00, $u.'photo-1598515214211-89d3c73ae83b?w=400&h=240&fit=crop', false],
                ['Monte sua Massa', 'Escolha a massa, o molho e até 8 ingredientes. Monte do seu jeito.', 'Serve 1 pessoa', 35.00, $u.'photo-1563379926898-05f4575a45d8?w=400&h=240&fit=crop', true],
                ['Picanha Grelhada', 'Ponto a gosto. Acompanha mandioca frita e vinagrete.', 'Serve 2 pessoas', 69.90, $u.'photo-1544025162-d76694265947?w=400&h=240&fit=crop', false],
            ],
            'espeto' => [
                ['Espeto de Frango', 'Temperado com alho e ervas finas.', 'Serve 3 unidades', 18.00, $u.'photo-1529563021893-cc83c992d75d?w=400&h=240&fit=crop', false],
                ['Espeto de Carne', 'Alcatra macia na brasa.', 'Serve 3 unidades', 22.00, $u.'photo-1555939594-58d7cb561ad1?w=400&h=240&fit=crop', false],
                ['Espeto de Queijo', 'Coalho dourado com mel e orégano.', 'Serve 2 unidades', 16.00, $u.'photo-1589302168068-964664d93dc0?w=400&h=240&fit=crop', false],
                ['Espeto de Legumes', 'Abobrinha, pimentão e cebola grelhados. Vegano.', 'Serve 2 unidades', 14.00, $u.'photo-1512058564366-18510be2db19?w=400&h=240&fit=crop', false],
            ],
            'sides' => [
                ['Mandioca Frita', 'Crocante por fora, macia por dentro. Com maionese da casa.', 'Serve 2 pessoas', 14.00, $u.'photo-1630614025809-5cc4e1e3a7ac?w=400&h=240&fit=crop', false],
                ['Farofa Especial', 'Farofa de bacon com ovos e cebolinha.', 'Serve 2 pessoas', 12.00, $u.'photo-1598524695673-f73ac0a2fd83?w=400&h=240&fit=crop', false],
                ['Couve Refogada', 'Couve mineira no alho e azeite.', 'Serve 2 pessoas', 10.00, $u.'photo-1576045057995-568f588f82fb?w=400&h=240&fit=crop', false],
                ['Arroz Branco', 'Arroz soltinho temperado com alho e sal.', 'Serve 2 pessoas', 8.00, $u.'photo-1536304993881-ff86e0c9b29f?w=400&h=240&fit=crop', false],
            ],
            'drinks' => [
                ['Caipirinha', 'Cachaça artesanal, limão, açúcar e gelo.', 'Serve 1 pessoa', 18.00, $u.'photo-1541614101331-1a5a3a194e92?w=400&h=240&fit=crop', false],
                ['Suco Natural', 'Laranja, maracujá, abacaxi ou melão. 300ml.', 'Serve 1 pessoa', 12.00, $u.'photo-1600271886742-f049cd451bba?w=400&h=240&fit=crop', false],
                ['Água Mineral', 'Sem gás ou com gás. 500ml.', 'Serve 1 pessoa', 6.00, $u.'photo-1548839140-29a749e1cf4d?w=400&h=240&fit=crop', false],
                ['Refrigerante', 'Coca-Cola, Guaraná ou Sprite. Lata 350ml.', 'Serve 1 pessoa', 8.00, $u.'photo-1629203851122-3726ecdf080e?w=400&h=240&fit=crop', false],
            ],
            'doces' => [
                ['Pudim de Leite', 'Receita da casa com calda de caramelo.', 'Serve 1 pessoa', 16.00, $u.'photo-1551024506-0bccd828d307?w=400&h=240&fit=crop', false],
                ['Trio de Brigadeiros', 'Tradicional, pistache e maracujá.', 'Serve 1 pessoa', 14.00, $u.'photo-1599599810769-bcde5a160d32?w=400&h=240&fit=crop', false],
                ['Sorvete Artesanal', 'Creme, chocolate ou morango. 2 bolas.', 'Serve 1 pessoa', 13.00, $u.'photo-1563805042-7684c019e1cb?w=400&h=240&fit=crop', false],
            ],
        ];

        foreach ($catalog as $catSlug => $items) {
            $category = Category::where('slug', $catSlug)->first();
            if (! $category) {
                continue;
            }

            foreach ($items as [$name, $desc, $serves, $price, $img, $customizable]) {
                Product::updateOrCreate(
                    ['category_id' => $category->id, 'name' => $name],
                    [
                        'description'     => $desc,
                        'serves'          => $serves,
                        'price'           => $price,
                        'image'           => $img,
                        'available'       => true,
                        'is_customizable' => $customizable,
                    ]
                );
            }
        }
    }
}
