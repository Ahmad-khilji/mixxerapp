<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminTicketController;
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
    return view('welcome');
});


Route::prefix('dashboard')->middleware(['auth'])->name('dashboard-')->group(function () {
    Route::get('/', [AdminController::class, 'index']);

    Route::prefix('user')->name('user-')->group(function () {
        Route::get('/', [AdminController::class, 'list']);
    });


    Route::prefix('faqs')->name('faqs-')->group(function () {
        Route::get('/', [AdminController::class, 'faqs']);
        Route::post('add', [AdminController::class, 'addFaq'])->name('add');
        Route::post('edit/{id}', [AdminController::class, 'editFaq'])->name('edit');
        Route::get('delete-faq/{id}', [AdminController::class, 'deleteFaq'])->name('delete');
    });

    Route::prefix('ticket')->name('ticket-')->group(function () {
        Route::get('/{status}', [AdminTicketController::class, 'ticket'])->name('ticket');
        Route::get('close-ticket/{id}', [AdminTicketController::class, 'closeTicket'])->name('close-ticket');
        Route::get('{status}/messages/{sendBy}', [AdminTicketController::class, 'messages'])->name('messages');
        Route::post('send-message', [AdminTicketController::class, 'sendMessage'])->name('send-message');
    });


    Route::prefix('report')->name('report-')->group(function () {
        Route::get('/{type}', [AdminReportController::class, 'report']);
        
    });
});


require __DIR__ . '/auth.php';
