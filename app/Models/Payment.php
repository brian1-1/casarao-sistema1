<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Pagamento de uma comanda.
 * Métodos: pix | dinheiro | cartao.
 */
class Payment extends Model
{
    protected $fillable = ['table_id', 'amount', 'method', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Rótulo amigável do método de pagamento.
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'pix'      => 'Pix',
            'dinheiro' => 'Dinheiro',
            'cartao'   => 'Cartão',
            default    => $this->method,
        };
    }
}
