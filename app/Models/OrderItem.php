<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Item de um pedido.
 * Possui status próprio (enviado | em_preparo | pronto), pode ser cancelado
 * com motivo obrigatório, e pode carregar uma customização de "Monte sua Massa".
 */
class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'notes',
        'status', 'customization', 'cancelled_at', 'cancel_reason', 'cancelled_by',
    ];

    protected $casts = [
        'unit_price'    => 'decimal:2',
        'quantity'      => 'integer',
        'customization' => 'array',
        'cancelled_at'  => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Subtotal do item (quantidade x preço unitário).
     * Itens cancelados não entram no total da comanda.
     */
    public function getSubtotalAttribute(): float
    {
        if ($this->isCancelled()) {
            return 0.0;
        }

        return (float) ($this->quantity * $this->unit_price);
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }

    /**
     * É um item personalizado do tipo "Monte sua Massa"?
     */
    public function isCustom(): bool
    {
        return ! empty($this->customization);
    }

    /**
     * Rótulo amigável do status do item.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->isCancelled()) {
            return 'Cancelado';
        }

        return match ($this->status) {
            'enviado'    => 'Enviado',
            'em_preparo' => 'Em preparo',
            'pronto'     => 'Pronto',
            default      => $this->status,
        };
    }

    /**
     * Descrição legível da customização (ex: "Talharim · Molho à bolonhesa · + Bacon, Parmesão").
     */
    public function getCustomizationLabelAttribute(): ?string
    {
        if (! $this->isCustom()) {
            return null;
        }

        $c = $this->customization;
        $parts = [];

        if (! empty($c['massa'])) {
            $parts[] = $c['massa'];
        }
        if (! empty($c['molho'])) {
            $parts[] = $c['molho'];
        }
        if (! empty($c['ingredientes']) && is_array($c['ingredientes'])) {
            $parts[] = '+ ' . implode(', ', $c['ingredientes']);
        }

        return implode(' · ', $parts);
    }
}
