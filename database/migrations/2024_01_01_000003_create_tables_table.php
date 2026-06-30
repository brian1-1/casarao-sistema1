<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mesas do restaurante.
 * Status possíveis: livre, ocupada, fechada.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique();   // número da mesa (identificação única)
            $table->enum('status', ['livre', 'ocupada', 'fechada'])->default('livre');
            $table->timestamp('opened_at')->nullable(); // horário de abertura da comanda
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
