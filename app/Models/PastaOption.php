<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Opção disponível para o "Monte sua Massa": massa, molho ou ingrediente.
 */
class PastaOption extends Model
{
    public const MASSA = 'massa';
    public const MOLHO = 'molho';
    public const INGREDIENTE = 'ingrediente';

    /**
     * Limite máximo de ingredientes que podem ser escolhidos por prato.
     * Usado tanto na validação do frontend quanto na revalidação do backend.
     */
    public const MAX_INGREDIENTES = 8;

    protected $fillable = ['type', 'name', 'extra_price', 'active', 'sort_order'];

    protected $casts = [
        'extra_price' => 'decimal:2',
        'active'      => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
