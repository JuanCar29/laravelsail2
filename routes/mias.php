<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CategoriasManager;
use App\Livewire\ProductosManager;
use App\Livewire\PreferenciasManager;
use App\Livewire\RecibosManager;
use App\Livewire\PosManager;
use App\Livewire\IvasManager;
use App\Livewire\UsersManager;
use App\Livewire\CocinasManager;
use App\Livewire\RecibosAdmin;
use App\Livewire\RegistrosManager;
use App\Livewire\VentasManager;
use App\Livewire\CajaManager;
use App\Http\Controllers\ReciboPdfController;
use App\Livewire\ReservasManager;
use App\Http\Controllers\CajaController;

Route::middleware(['auth'])->prefix('administrador')->group(function () {
     
    Route::middleware('level:1')->group(function () {
        Route::get('/registros', RegistrosManager::class)->name('registros');
        Route::get('/categorias', CategoriasManager::class)->name('categorias');
        Route::get('/productos', ProductosManager::class)->name('productos');
        Route::get('/preferencias', PreferenciasManager::class)->name('preferencias');
        Route::get('/recibos-admin', RecibosAdmin::class)->name('recibos-admin');
        Route::get('/users', UsersManager::class)->name('users');
        Route::get('/ivas', IvasManager::class)->name('ivas');
        Route::get('/ventas', VentasManager::class)->name('ventas');
        Route::get('/caja', CajaManager::class)->name('caja');
        Route::get('/cajas', [CajaController::class, 'index'])->name('cajas');
    });
    
    Route::middleware('level:1,2')->group(function () {
        Route::get('/recibos', RecibosManager::class)->name('recibos');
        Route::get('/pos/{recibo}', PosManager::class)->name('pos');
        Route::get('/reservas', ReservasManager::class)->name('reservas');      
    });
    
    Route::middleware('level:1,3')->group(function () {
        Route::get('/cocinas', CocinasManager::class)->name('cocinas');       
    });
    
    Route::get('/recibo-pdf/{recibo}', ReciboPdfController::class)->name('recibo-pdf');
});
