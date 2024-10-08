<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::put('/{id}', [UserController::class, 'update'])->name('update')->whereUuid(['id']);
        Route::post('/rollback', [UserController::class, 'rollbackById'])->name('rollbackById');
        Route::post('/rollback/{id}', [UserController::class, 'rollbackByIds'])->name('rollbackByIds')->whereUuid(['id']);
        Route::get('/deleted', [UserController::class, 'getDeletedList'])->name('deletedList');
        Route::get('/{id}', [UserController::class, 'getById'])->name('getById')->whereUuid(['id']);
        Route::get('/', [UserController::class, 'getList'])->name('list');
        Route::delete('/forever', [UserController::class, 'deleteForeverByIds'])->name('deleteForeverByIds');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('delete')->whereUuid(['id']);
        Route::delete('/{id}/forever', [UserController::class, 'deleteForever'])->name('deleteForever')->whereUuid(['id']);
        Route::delete('/', [UserController::class, 'deleteByIds'])->name('deleteByIds');
    });
});
Route::group(['middleware' => 'guest'], function () {
    Route::post('/login', [UserController::class, 'login'])->name('rollbackById');
    Route::post('/registration', [UserController::class, 'create'])->name('create');
    Route::post('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('resetPassword')->whereUuid(['id']);
});
