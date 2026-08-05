<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\SolicitacoesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])->name('dashboard.super-admin');
    Route::get('/dashboard/admin-setor', [DashboardController::class, 'adminSetor'])->name('dashboard.admin-setor');
    
    Route::get('/setores/create', [SetorController::class, 'create'])->name('setores.create');
    Route::post('/setores', [SetorController::class, 'store'])->name('setores.store');

    Route::get('/setores/{setor}/funcionarios/create', [FuncionarioController::class, 'create'])->name('funcionarios.create');
    Route::post('/setores/{setor}/funcionarios', [FuncionarioController::class, 'store'])->name('funcionarios.store');
    Route::patch('/funcionarios/{funcionario}/setor', [FuncionarioController::class, 'transfer'])->name('funcionarios.transfer');
    Route::delete('/funcionarios/{funcionario}', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');

    Route::post('/solicitacoes/transferencia', [SolicitacoesController::class, 'storeTransferencia'])->name('solicitacoes.transferencia.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
