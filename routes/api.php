<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\customerController;

Route::get('/customer',[customerController::class, 'list']);
Route::get('/customer/{id}', [customerController::class, 'show']);
Route::post('/customer', [customerController::class, 'store']);
Route::put('/customer/{id}', [customerController::class, 'update']);
Route::patch('/customer/{id}', [customerController::class, 'partialUpdate']);
Route::delete('/customer/{id}', [customerController::class, 'destroy']);

Route::get('/vendor',[customerController::class, 'list']);
Route::get('/vendor/{id}', [customerController::class, 'show']);
Route::post('/vendor', [customerController::class, 'store']);
Route::put('/vendor/{id}', [customerController::class, 'update']);
Route::patch('/vendor/{id}', [customerController::class, 'partialUpdate']);
Route::delete('/vendor/{id}', [customerController::class, 'destroy']);

Route::get('/product',[customerController::class, 'list']);
Route::get('/product/{id}', [customerController::class, 'show']);
Route::post('/product', [customerController::class, 'store']);
Route::put('/product/{id}', [customerController::class, 'update']);
Route::patch('/product/{id}', [customerController::class, 'partialUpdate']);
Route::delete('/product/{id}', [customerController::class, 'destroy']);


Route::get('ventas', [VentaController::class, 'index']);
Route::post('ventas', [VentaController::class, 'store']);
Route::get('ventas/{numFac}', [VentaController::class, 'show']);

Route::post('pagos', [PagoController::class, 'iniciarPago']);

Route::get('pagos/iniciar/{ventaId}', [PagoController::class, 'iniciarPago'])->name('pagos.iniciar');


Route::post('webhook/culqi', [WebhookController::class, 'culqi']);
Route::post('webhook/coingate', [WebhookController::class, 'coingate']);


Route::get('/pagos/success', [PagoController::class, 'success'])->name('pagos.success');
Route::get('/pagos/failure', [PagoController::class, 'failure'])->name('pagos.failure');
Route::get('/pagos/pending', [PagoController::class, 'pending'])->name('pagos.pending');

Route::post('/pagos/notification', [PagoController::class, 'notification'])->name('pagos.notification');
