<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ventaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\vendorController;
use App\Http\Controllers\VentaDetalleController;

Route::get('/customer',[customerController::class, 'list']);
Route::get('/customer/{id}', [customerController::class, 'show']);
Route::post('/customer', [customerController::class, 'store']);
Route::put('/customer/{id}', [customerController::class, 'update']);
Route::patch('/customer/{id}', [customerController::class, 'partialUpdate']);
Route::delete('/customer/{id}', [customerController::class, 'destroy']);

Route::get('/vendor',[vendorController::class, 'list']);
Route::get('/vendor/{id}', [vendorController::class, 'show']);
Route::post('/vendor', [vendorController::class, 'store']);
Route::put('/vendor/{id}', [vendorController::class, 'update']);
Route::patch('/vendor/{id}', [vendorController::class, 'partialUpdate']);
Route::delete('/vendor/{id}', [vendorController::class, 'destroy']);

Route::get('/product',[ProductController::class, 'list']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::post('/product', [ProductController::class, 'store']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::patch('/product/{id}', [ProductController::class, 'partialUpdate']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);


Route::get('/ventas', [ventaController::class, 'list']);
Route::post('/ventas', [ventaController::class, 'store']);
Route::get('/ventas/{numFac}', [ventaController::class, 'show']);
Route::delete('/ventas/{numFac}', [ventaController::class, 'destroy']);

Route::get('/ventadetalle', [VentaDetalleController::class, 'list']);
Route::get('/ventadetalle/{RefVentaId}', [VentaDetalleController::class, 'show']);


Route::get('/pagos', [PagoController::class, 'list']);
Route::get('/pagos/{id}',[PagoController::class, 'show']);
Route::post('/pagos', [PagoController::class, 'iniciarPago']);

Route::get('/pagos/iniciar/{ventaId}', [PagoController::class, 'iniciarPago'])->name('pagos.iniciar');


Route::post('/webhook/culqi', [WebhookController::class, 'culqi']);
Route::post('/webhook/coingate', [WebhookController::class, 'coingate']);


Route::get('/pagos/success', [PagoController::class, 'success'])->name('pagos.success');
Route::get('/pagos/failure', [PagoController::class, 'failure'])->name('pagos.failure');
Route::get('/pagos/pending', [PagoController::class, 'pending'])->name('pagos.pending');

Route::post('/pagos/notification', [PagoController::class, 'notification'])->name('pagos.notification');
