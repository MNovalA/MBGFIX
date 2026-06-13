<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Beranda Utama
Route::get('/', function () {
    return view('index'); // Mengarah ke index.blade.php
});

// --- Rute Halaman Monitoring (View List) ---
Route::get('/sekolahs', function () { return view('sekolah'); });
Route::get('/dapurs', function () { return view('dapur'); });
Route::get('/menus', function () { return view('menu'); });
Route::get('/inventories', function () { return view('inventory'); });
Route::get('/shipments', function () { return view('shipment'); });

// --- Rute Halaman Kelola (CRUD) ---
Route::get('/kelola/sekolah', function () { return view('crudSekolah'); });
Route::get('/kelola/dapur', function () { return view('crudDapur'); });
Route::get('/kelola/menu', function () { return view('crudMenu'); });
Route::get('/kelola/inventory', function () { return view('crudInventory'); });
Route::get('/kelola/distribusi', function () { return view('crudDistribusi'); });