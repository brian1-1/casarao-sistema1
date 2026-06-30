<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona ao produto:
 * - requires_meat_point: marca pratos de carne que exigem que o cliente
 *   escolha o ponto da carne (mal passado, ao ponto, bem passado) antes
 *   de adicionar à comanda.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('requires_meat_point')->default(false)->after('is_customizable');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('requires_meat_point');
        });
    }
};
