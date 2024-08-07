<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(RouteServiceProvider::HOME);
});

Route::get("/login", [LoginController::class, 'showLoginForm'])->middleware('guest')->name("login");
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->name("logout");

Route::middleware(['auth', 'status:normal'])->group(function () {
    Route::get("/profile", [ProfileController::class, 'index'])->name("profile");
    Route::put("/profile", [ProfileController::class, 'update']);
    Route::patch("/profile", [ProfileController::class, 'patch']);

    Route::get("/customers", [CustomerController::class, 'index'])->name("customers");
    Route::get("/customers2", [CustomerController::class, 'selectize'])->name("customers2");
    Route::get("/customers/{id}", [CustomerController::class, 'get'])->name("customer");
    Route::put("/customers/{id}", [CustomerController::class, 'update']);
    Route::post("/customers", [CustomerController::class, 'store']);
    Route::delete("/customers", [CustomerController::class, 'destroy']);
    Route::get("/getCitys", [CustomerController::class, 'getCitys'])->name("get.citys");

    Route::get("/invoices", [InvoiceController::class, 'index'])->name("invoices");
    Route::get("/invoices/{id}", [InvoiceController::class, 'get'])->name("invoice");
    Route::put("/invoices/{id}", [InvoiceController::class, 'update']);
    Route::patch("/invoices/{id}", [InvoiceController::class, 'patch']);
    Route::post("/invoices/{id}", [InvoiceController::class, 'store']);

    Route::get("/payments", [PaymentController::class, 'index'])->name('payments');
    Route::put("/payments/{id}", [PaymentController::class, 'update'])->name('payment');
    Route::patch("/payments/{id}", [PaymentController::class, 'patch']);
    Route::post("/payments", [PaymentController::class, 'store']);
    Route::delete("/payments", [PaymentController::class, 'destroy']);
});

Route::middleware(['auth', 'user', 'status:normal'])->group(function () {
    Route::post("/loginAs/{id}", [ApplicationController::class, 'loginAs'])->name("loginAs");
    Route::get("/applications", [ApplicationController::class, 'index'])->name("applications");
    Route::post("/applications", [ApplicationController::class, 'store']);
    Route::patch("/applications", [ApplicationController::class, 'patch']);
});

Route::get("/notice/{id}", [NoticeController::class, 'index'])->name("notice");

// CACHE CLEAR
Route::get('/clearCache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');

    echo "success";
});
