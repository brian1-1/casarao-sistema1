<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Categorias de produtos do cardápio
 * (Pratos do Dia, Espetinhos, Acompanhamentos, Bebidas, Sobremesas...).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();      // identificador (ex: pratos, espeto)
            $table->string('name');                // nome exibido
            $table->string('icon')->nullable();    // classe do ícone (Tabler Icons)
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
