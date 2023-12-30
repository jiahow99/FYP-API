<?php

use App\Http\Controllers\FCMController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/store', [StoreController::class, 'index']);
Route::post('/store/create', [StoreController::class, 'create']);
Route::get('/store/uid/{uid}', [StoreController::class, 'showByUid']);
Route::get('/store/place-id/{placeId}', [StoreController::class, 'showByPlaceId']);
Route::put('/store/{uid}/update-token', [StoreController::class, 'updateToken']);
Route::post('/verify-stores', [StoreController::class, 'verifyStores']);

Route::get('/parts', [PartController::class, 'index']);
Route::get('/part/{part}', [PartController::class, 'showPart']);
Route::get('/tyres', [PartController::class, 'allTyres']);
Route::get('/tyre/{tyre}', [PartController::class, 'showTyre']);

// FCM
Route::post('/send-mechanic-request', [FCMController::class, 'sendRequest']);
Route::post('/request-tyre-change', [FCMController::class, 'requestTyreChange']);
Route::post('/accept-request', [FCMController::class, 'acceptRequest']);
Route::post('/complete-request', [FCMController::class, 'completeRequest']);