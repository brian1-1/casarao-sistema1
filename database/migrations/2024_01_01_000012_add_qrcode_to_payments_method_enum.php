<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona o método de pagamento "qrcode" ao enum de payments.method.
 * Alterar um ENUM exige SQL bruto (doctrine/dbal não suporta ENUM no MySQL).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY method ENUM('pix', 'dinheiro', 'cartao', 'qrcode') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY method ENUM('pix', 'dinheiro', 'cartao') NOT NULL");
    }
};
