<?php

use Illuminate\Support\Facades\Route;
use LaravelReady\VitePress\Http\Controllers\VitePressController;

Route::get('/', [VitePressController::class, 'index'])->name('index');
Route::get('/{path}', [VitePressController::class, 'show'])
    ->where('path', '.*')
    ->name('show');
