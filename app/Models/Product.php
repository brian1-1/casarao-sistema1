<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Produto do cardápio.
 */
class Product extends Model
{
    /**
     * Pontos de carne disponíveis para pratos marcados com requires_meat_point.
     * Chave usada no JSON de customização => rótulo exibido ao cliente.
     */
    const MEAT_POINTS = [
        'mal_passado' => 'Mal passado',
        'ao_ponto'    => 'Ao ponto',
        'bem_passado' => 'Bem passado',
    ];

    protected $fillable = [
        'category_id', 'name', 'description', 'serves', 'price', 'image', 'available',
        'is_customizable', 'requires_meat_point',
    ];

    protected $casts = [
        'price'                => 'decimal:2',
        'available'            => 'boolean',
        'is_customizable'      => 'boolean',
        'requires_meat_point'  => 'boolean',
    ];

    /**
     * Categoria à qual o produto pertence.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Retorna a URL da imagem ou um placeholder padrão quando não houver imagem.
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/placeholder.svg');
        }

        // URL absoluta (ex: imagens externas)
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // Arquivo armazenado em storage/app/public
        return asset('storage/' . $this->image);
    }
}
