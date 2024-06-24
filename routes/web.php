<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
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
  return view('welcome');
});

Route::get("/login", [LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->name("logout");

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get("/customers", [CustomerController::class, 'index'])->name("customers");
Route::get("/customers2", [CustomerController::class, 'selectize'])->name("customers2");
Route::get("/customers/{id}", [CustomerController::class, 'get'])->name("customer");
Route::put("/customers/{id}", [CustomerController::class, 'update']);
Route::post("/customers", [CustomerController::class, 'store']);
Route::delete("/customers", [CustomerController::class, 'destroy']);

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

Route::get("/applications", [ApplicationController::class, 'index'])->name("applications");
Route::post("/applications", [ApplicationController::class, 'store']);