<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pedidos (comandas enviadas para a cozinha).
 * Status: recebido, em_preparo, pronto, entregue.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quem registrou
            $table->enum('status', ['recebido', 'em_preparo', 'pronto', 'entregue'])->default('recebido');
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();   // observações gerais do pedido
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
