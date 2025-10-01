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
    return response()->json([
        'message' => 'ちくぎん地域経済研究所 API',
        'status' => 'running',
        'version' => '1.0.0'
    ]);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Laravel認証ミドルウェア用のloginルート（ダミー）
Route::get('/login', function () {
    return response()->json([
        'error' => 'Unauthorized',
        'message' => 'Authentication required. Please use API endpoints for authentication.',
        'login_endpoint' => '/api/admin/login'
    ], 401);
})->name('login');
