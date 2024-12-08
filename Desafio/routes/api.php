<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::middleware('api')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/upload/history', [FileController::class, 'uploadHistory']);
    Route::get('/file/search', [FileController::class, 'searchContent']);
});
