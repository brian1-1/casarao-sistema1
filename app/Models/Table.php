<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Mesa do restaurante.
 * Status: livre | ocupada | fechada.
 */
class Table extends Model
{
    protected $fillable = ['number', 'status', 'opened_at'];

    protected $casts = [
        'opened_at' => 'datetime',
    ];

    /**
     * Pedidos da mesa.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Pedidos ativos (da comanda atual, ainda não pagos/entregues completamente).
     * Considera os pedidos criados após a abertura da comanda.
     */
    public function activeOrders()
    {
        return $this->hasMany(Order::class)
            ->when($this->opened_at, fn ($q) => $q->where('created_at', '>=', $this->opened_at));
    }

    /**
     * Pagamentos da mesa.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Transferências em que esta mesa foi origem.
     */
    public function transfersOut()
    {
        return $this->hasMany(TableTransfer::class, 'from_table_id');
    }

    /**
     * Transferências em que esta mesa foi destino.
     */
    public function transfersIn()
    {
        return $this->hasMany(TableTransfer::class, 'to_table_id');
    }

    /**
     * Valor parcial da comanda atual (soma dos totais dos pedidos ativos).
     */
    public function getPartialTotalAttribute(): float
    {
        return (float) $this->activeOrders()->sum('total');
    }

    /**
     * Rótulo amigável do status com emoji.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'livre'   => '🟢 Livre',
            'ocupada' => '🟡 Ocupada',
            'fechada' => '🔴 Fechada',
            default   => $this->status,
        };
    }
}
