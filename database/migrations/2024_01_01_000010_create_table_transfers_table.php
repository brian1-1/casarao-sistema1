<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Registro de auditoria das transferências de mesa.
 * Guarda de qual mesa para qual mesa, quem transferiu e quando.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_table_id')->constrained('tables')->cascadeOnDelete();
            $table->foreignId('to_table_id')->constrained('tables')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quem transferiu
            $table->integer('orders_moved')->default(0); // quantos pedidos foram movidos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_transfers');
    }
};
