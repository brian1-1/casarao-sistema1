<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona ao produto:
 * - serves: informação "serve X pessoas" exibida no cardápio.
 * - is_customizable: marca produtos do tipo "Monte sua Massa",
 *   que exigem escolha de massa, molho e ingredientes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('serves')->nullable()->after('description'); // ex: "Serve 1 pessoa"
            $table->boolean('is_customizable')->default(false)->after('available');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['serves', 'is_customizable']);
        });
    }
};
