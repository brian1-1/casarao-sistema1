<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pagamentos das comandas.
 * Métodos: pix, dinheiro, cartao.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['pix', 'dinheiro', 'cartao']);
            $table->enum('status', ['pendente', 'pago'])->default('pago');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
