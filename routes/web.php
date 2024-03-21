<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(url('/dns-records'));
});

Route::get('/dns-records', App\Livewire\ShowDnsRecords::class);
Route::get('/dns-records/create', App\Livewire\CreateDnsRecord::class);
Route::get('/dns-records/edit/{id}', App\Livewire\EditDnsRecord::class);
