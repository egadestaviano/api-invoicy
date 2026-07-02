<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;

Route::get('/health', function () {
    $databaseOk = false;

    try {
        DB::select('SELECT 1');
        $databaseOk = true;
    } catch (\Throwable $e) {
        $databaseOk = false;
    }

    return response()->json([
        'status' => $databaseOk ? 'ok' : 'degraded',
        'api' => true,
        'database' => $databaseOk,
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Test Network
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'pong'
    ]); 
});

// Invoice Routes
Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/', [InvoiceController::class, 'store']);
    Route::get('/{invoice}', [InvoiceController::class, 'show']);
    Route::put('/{invoice}', [InvoiceController::class, 'update']);
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy']);
    Route::get('/{invoice}/pdf', [InvoiceController::class, 'generatePdf']);
    Route::get('/{invoice}/logo', [InvoiceController::class, 'getLogo']);
    Route::get('/statistics/overview', [InvoiceController::class, 'statistics']);

    // Invoice Items Routes
    Route::get('/{invoice}/items', [InvoiceItemController::class, 'index']);
    Route::post('/{invoice}/items', [InvoiceItemController::class, 'store']);
    Route::get('/{invoice}/items/{item}', [InvoiceItemController::class, 'show']);
    Route::put('/{invoice}/items/{item}', [InvoiceItemController::class, 'update']);
    Route::delete('/{invoice}/items/{item}', [InvoiceItemController::class, 'destroy']);
});
