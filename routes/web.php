<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PersonalizarController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthSimpleController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('home'); // resources/views/home.blade.php
})->name('home');

Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/paquete/{slug}', [CatalogoController::class, 'show'])->name('paquete.show');

Route::get('/personalizar/{slug}', [PersonalizarController::class, 'create'])->name('personalizar.create');
Route::post('/personalizar',        [PersonalizarController::class, 'store'])->name('personalizar.store');
Route::get('/personalizacion/{id}', [PersonalizarController::class, 'show'])->name('personalizacion.show');

Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar/{item}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('/carrito/eliminar/{item}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/reserva/{codigo}', [CheckoutController::class, 'show'])->name('reserva.show');

Route::get('/ingresar',  [AuthSimpleController::class, 'loginForm'])->name('auth.login');
Route::post('/ingresar', [AuthSimpleController::class, 'login']);
Route::get('/registrar', [AuthSimpleController::class, 'registerForm'])->name('auth.register');
Route::post('/registrar',[AuthSimpleController::class, 'register']);
Route::post('/salir',    [AuthSimpleController::class, 'logout'])->name('auth.logout');

// Ruta protegida por el middleware 'admin'
Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('admin');

