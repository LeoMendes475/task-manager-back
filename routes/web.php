<?php

use App\Http\Controllers\Admin\{ReplySupportController, SupportController};
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::group([
    'prefix' => 'task',
], function () {
    Route::post('/create', [TaskController::class, 'create']);
    Route::get('/findAll', [TaskController::class, 'findAll']);
    Route::get('/findById/{id}', [TaskController::class, 'findById']);
    Route::patch('/update/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'deleteById']);
});

require __DIR__ . '/auth.php';
