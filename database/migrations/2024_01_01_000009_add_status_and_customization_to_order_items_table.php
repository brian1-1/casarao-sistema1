<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Evolui order_items para suportar:
 * - status individual do item (enviado, em_preparo, pronto) — item 4/12/13 do PDF.
 * - customization: JSON com a escolha de massa/molho/ingredientes do "Monte sua Massa".
 * - cancelamento de item com motivo obrigatório registrado — item 8 do PDF.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('status', ['enviado', 'em_preparo', 'pronto'])->default('enviado')->after('notes');
            $table->json('customization')->nullable()->after('status'); // {"massa":"...","molho":"...","ingredientes":[...]}
            $table->timestamp('cancelled_at')->nullable()->after('customization');
            $table->string('cancel_reason')->nullable()->after('cancelled_at');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete()->after('cancel_reason');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cancelled_by');
            $table->dropColumn(['status', 'customization', 'cancelled_at', 'cancel_reason']);
        });
    }
};
