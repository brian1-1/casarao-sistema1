<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Categoria de produtos do cardápio.
 */
class Category extends Model
{
    protected $fillable = ['slug', 'name', 'icon', 'sort_order', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Produtos pertencentes a esta categoria.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Apenas produtos disponíveis.
     */
    public function availableProducts()
    {
        return $this->hasMany(Product::class)->where('available', true);
    }
}
