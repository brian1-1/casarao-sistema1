<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CozinhaController;
use App\Http\Controllers\GarcomController;
use App\Http\Controllers\Gerente\CategoryController;
use App\Http\Controllers\Gerente\PastaOptionController;
use App\Http\Controllers\Gerente\ProductController;
use App\Http\Controllers\GerenteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Web — Sistema de Gestão "O Casarão"
|--------------------------------------------------------------------------
*/

// Página inicial → redireciona para o login
Route::get('/', fn () => redirect()->route('login'));

// ---------- Autenticação ----------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Todas as rotas abaixo exigem usuário autenticado
Route::middleware('auth')->group(function () {

    // ---------- Mesa do Cliente (Cliente, Garçom e Gerente podem operar) ----------
    Route::middleware('role:cliente,garcom,gerente')->prefix('cliente')->name('cliente.')->group(function () {
        Route::get('/mesas', [ClienteController::class, 'mesas'])->name('mesas');
        Route::get('/mesa/{table}', [ClienteController::class, 'menu'])->name('menu');
        Route::post('/mesa/{table}/item', [ClienteController::class, 'addItem'])->name('item.add');

        // "Monte sua Massa": adiciona ao carrinho um item customizado (massa + molho + ingredientes)
        Route::post('/mesa/{table}/item/customizado', [ClienteController::class, 'addCustomItem'])->name('item.add_custom');

        Route::post('/mesa/{table}/item/qty', [ClienteController::class, 'updateQty'])->name('item.qty');
        Route::post('/mesa/{table}/item/remove', [ClienteController::class, 'removeItem'])->name('item.remove');
        Route::post('/mesa/{table}/confirmar', [ClienteController::class, 'confirm'])->name('confirm');
        Route::post('/mesa/{table}/pagar', [ClienteController::class, 'pay'])->name('pay');

        // Cancelamento de um item já enviado à cozinha, com motivo obrigatório
        Route::post('/mesa/{table}/item-enviado/{orderItem}/cancelar', [ClienteController::class, 'cancelItem'])->name('item.cancel');

        // Transferência de mesa (move a comanda e os itens, com auditoria)
        Route::post('/mesa/{table}/transferir', [ClienteController::class, 'transfer'])->name('transfer');
    });

    // ---------- Painel do Garçom ----------
    Route::middleware('role:garcom,gerente')->prefix('garcom')->name('garcom.')->group(function () {
        Route::get('/', [GarcomController::class, 'index'])->name('index');
        Route::post('/pedido/{order}/entregar', [GarcomController::class, 'deliver'])->name('deliver');
    });

    // ---------- Painel da Cozinha ----------
    Route::middleware('role:cozinha,gerente')->prefix('cozinha')->name('cozinha.')->group(function () {
        Route::get('/', [CozinhaController::class, 'index'])->name('index');

        // Avança o status de um item específico (enviado -> em_preparo -> pronto)
        Route::post('/item/{orderItem}/status', [CozinhaController::class, 'updateItemStatus'])->name('item.status');
    });

    // ---------- Painel do Gerente ----------
    Route::middleware('role:gerente')->prefix('gerente')->name('gerente.')->group(function () {
        Route::get('/', [GerenteController::class, 'dashboard'])->name('dashboard');

        // CRUD de Produtos
        Route::resource('products', ProductController::class)->except('show');

        // CRUD de Categorias
        Route::resource('categories', CategoryController::class)->except('show');

        // CRUD das opções do "Monte sua Massa" (massa, molho, ingredientes)
        Route::resource('pasta-options', PastaOptionController::class)->except('show')->parameters([
            'pasta-options' => 'pastaOption',
        ]);
    });
});
