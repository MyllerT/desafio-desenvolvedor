<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'API está funcionando']);
});
Route::get('/health', function () {
    return response()->json(['status' => 'OK']);
});