<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LibrariansController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->middleware('notAdmin')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home')->middleware('notAdmin');
    Route::middleware('auth:member')->group(function () {
        Route::get('/books', [PageController::class, 'books'])->middleware('auth:member');
        Route::get('/history', [PageController::class, 'history'])->middleware('auth:member');
        Route::get('/books/{book}', [PageController::class, 'book'])->middleware('auth:member');
    });
});

// Login
Route::prefix('/login')->middleware('guest')->group(function () {
    Route::get('/', [PageController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// logout
Route::get('/logout', [AuthController::class, 'logout']);

// Admin & Pustakawan
Route::prefix('/dashboard')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [PageController::class, 'dashboard']);
    Route::middleware('isAdmin')->group(function () {
        Route::resource('/categories', CategoryController::class);
        Route::resource('/books', BookController::class);
        Route::resource('/members', MemberController::class);
        Route::get('/members/editpw/{member}', [MemberController::class, 'editPwView']);
        Route::put('/members/editpw/{member}', [MemberController::class, 'editPw']);
        Route::resource('/librarians', LibrariansController::class)->parameters([
            'librarians' => 'admin'
        ]);;
        Route::get('/librarians/editpw/{admin}', [LibrariansController::class, 'editPwView']);
        Route::put('/librarians/editpw/{admin}', [LibrariansController::class, 'editPw']);
    });

    Route::prefix('/reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/printtopdf', [ReportController::class, 'printToPdfMenu']);
        Route::post('/printtopdf', [ReportController::class, 'cetakPdf']);
        Route::get('/pdftemplate', [ReportController::class, 'pdfTemplate']);
    });
    Route::prefix('/borrows')->group(function () {
        Route::get('/', [BorrowController::class, 'index']);
        Route::post('/', [BorrowController::class, 'borrow']);
    });
    Route::prefix('/returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::post('/', [ReturnController::class, 'returns']);
        Route::get('/search/{f_idanggota}', [ReturnController::class, 'search']);
    });
});
