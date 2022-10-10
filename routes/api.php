<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AccountController;

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

Route::controller(AccountController::class)->group(function () {
    Route::post("/register", "register");
    Route::post("/login", "authenticate");
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(App\Http\Controllers\DepositController::class)->group(function () {
        Route::post("/deposit", "store");
    }); 
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AccountController::class)->group(function () {
        route::get("/logged", "testLogin");
        Route::get("/logout", "invalidate");
        Route::get('/profile', "getUser");
        Route::post('/profile/update', "updateProfile");
    });

    Route::middleware("auth.admin")->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get("/admin", "testAdmin");
            Route::get("/admin/profile/get/all", "getAllUser");
            Route::get("/admin/profile/get/{id}", "getUser");
            Route::post("/admin/profile/update/{id}", "updateUser");
        });
    });
});


Route::get("/", function (Request $request) {
    return [
        "app" => "bssj-api",
        "health" => "healthy",
        "repo" => "https://github.com/stackofsugar/bssj-api/",
        "copy" => "(C) 2022 - present BSSJ API Developers"
    ];
});
