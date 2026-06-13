<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\MenuController;

Route::get('/dapurs', [DapurController::class, 'index']);
Route::get('/dapurs/{id}', [DapurController::class, 'show']);
Route::post('/dapurs', [DapurController::class, 'store']);
Route::put('/dapurs/{id}', [DapurController::class, 'update']);
Route::delete('/dapurs/{id}', [DapurController::class, 'destroy']);

Route::get('/inventories', [InventoryController::class, 'index']);
Route::get('/inventories/dapur/{id_dapur}', [InventoryController::class, 'getByDapur']);
Route::get('/inventories/{id}', [InventoryController::class, 'show']);
Route::post('/inventories/reduce-bulk', [InventoryController::class, 'reduceBulkStock']);
Route::post('/inventories', [InventoryController::class, 'store']);
Route::put('/inventories/{id}', [InventoryController::class, 'update']);
Route::delete('/inventories/{id}', [InventoryController::class, 'destroy']);

Route::get('/shipments', [ShipmentController::class, 'index']);
Route::get('/shipments/{id}', [ShipmentController::class, 'show']);
Route::post('/shipments', [ShipmentController::class, 'store']);
Route::put('/shipments/{id}', [ShipmentController::class, 'update']);
Route::delete('/shipments/{id}', [ShipmentController::class, 'destroy']);

Route::get('/sekolahs', [SekolahController::class, 'index']);
Route::get('/sekolahs/{id}', [SekolahController::class, 'show']);
Route::post('/sekolahs', [SekolahController::class, 'store']);
Route::put('/sekolahs/{id}', [SekolahController::class, 'update']);
Route::delete('/sekolahs/{id}', [SekolahController::class, 'destroy']);

// Route untuk fungsi khusus
Route::get('/menus/kebutuhan/{id}/{id_sekolah}', [MenuController::class, 'getMenuKebutuhan']);
Route::post('/menus/process-production', [MenuController::class, 'processProduction']);

// Route CRUD Standar
Route::get('/menus', [MenuController::class, 'index']);
Route::get('/menus/{id}', [MenuController::class, 'show']);
Route::post('/menus', [MenuController::class, 'store']);
Route::put('/menus/{id}', [MenuController::class, 'update']);
Route::delete('/menus/{id}', [MenuController::class, 'destroy']);