<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route:: group(['prefix' => 'users'], function () {
    Route::get('', [UserController::class, 'index'])->name('api.users.index');
    Route::post('', [UserController::class, 'store'])->name('api.users.store');

    Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');
    Route::patch('/{id}', [UserController::class, 'update'])->name('api.users.update');
    Route::delete('/{id}', [UserController::class, 'delete'])->name('api.users.delete');
    Route::patch('/{id}/deactivate', [UserController::class, 'deactivate'])->name('api.users.deactivate');
});
