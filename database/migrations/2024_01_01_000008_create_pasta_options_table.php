<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Opções disponíveis para o "Monte sua Massa".
 * Tipos: massa, molho, ingrediente.
 * Cada ingrediente pode ter um preço adicional (0 para a maioria).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasta_options', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['massa', 'molho', 'ingrediente']);
            $table->string('name');
            $table->decimal('extra_price', 10, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasta_options');
    }
};
