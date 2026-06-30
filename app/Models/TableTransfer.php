<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Registro de auditoria de uma transferência de comanda entre mesas.
 */
class TableTransfer extends Model
{
    protected $fillable = ['from_table_id', 'to_table_id', 'user_id', 'orders_moved'];

    public function fromTable()
    {
        return $this->belongsTo(Table::class, 'from_table_id');
    }

    public function toTable()
    {
        return $this->belongsTo(Table::class, 'to_table_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
