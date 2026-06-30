<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Pedido enviado à cozinha.
 * O status do pedido é derivado do status dos seus itens (não-cancelados):
 * - 'entregue'   quando marcado manualmente pelo garçom.
 * - 'pronto'     quando todos os itens ativos estão prontos.
 * - 'em_preparo' quando ao menos um item está em preparo (e nenhum ainda só "enviado" pendente... na prática,
 *                em_preparo já cobre o caso misto, simplificando a visão do garçom/gerente).
 * - 'recebido'   quando todos os itens ainda estão "enviado".
 */
class Order extends Model
{
    protected $fillable = ['table_id', 'user_id', 'status', 'total', 'notes'];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Itens que ainda contam para o pedido (não cancelados).
     */
    public function activeItems()
    {
        return $this->hasMany(OrderItem::class)->whereNull('cancelled_at');
    }

    /**
     * Recalcula o total do pedido a partir dos itens não cancelados.
     */
    public function recalculateTotal(): void
    {
        $this->total = $this->items()
            ->whereNull('cancelled_at')
            ->get()
            ->sum(fn (OrderItem $item) => $item->quantity * $item->unit_price);

        $this->save();
    }

    /**
     * Recalcula o status do pedido a partir do status dos itens ativos.
     * Não sobrescreve 'entregue', que é definido manualmente pelo garçom.
     */
    public function recalculateStatus(): void
    {
        if ($this->status === 'entregue') {
            return;
        }

        $statuses = $this->items()->whereNull('cancelled_at')->pluck('status');

        if ($statuses->isEmpty()) {
            return;
        }

        if ($statuses->every(fn ($s) => $s === 'pronto')) {
            $this->status = 'pronto';
        } elseif ($statuses->contains('em_preparo') || $statuses->contains('pronto')) {
            $this->status = 'em_preparo';
        } else {
            $this->status = 'recebido';
        }

        $this->save();
    }

    /**
     * Rótulo amigável do status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'recebido'   => 'Pedido recebido',
            'em_preparo' => 'Em preparo',
            'pronto'     => 'Pronto',
            'entregue'   => 'Entregue',
            default      => $this->status,
        };
    }
}
