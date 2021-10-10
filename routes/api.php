<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/conversations', [App\Http\Controllers\Api\ConversationController::class,'store']);
Route::post('/accounts/{id}', [App\Http\Controllers\Api\AccountController::class,'update']);
