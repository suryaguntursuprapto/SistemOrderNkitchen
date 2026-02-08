<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\GoogleController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik & Otentikasi
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// --- OTENTIKASI UTAMA ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Max 5 attempts per minute
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1'); // Max 3 registrations per minute
    
    // --- RUTE LUPA PASSWORD (YANG HILANG SEBELUMNYA) ---
    Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
    
    // --- OTENTIKASI GOOGLE ---
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- VERIFIKASI EMAIL (OTP) ---
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Link-based verification (keep for backwards compatibility)
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Email berhasil diverifikasi!');
        }
        return redirect()->route('customer.dashboard')->with('success', 'Email berhasil diverifikasi!');
    })->middleware('signed')->name('verification.verify');

    // OTP-based verification
    Route::post('/email/verify-otp', [AuthController::class, 'verifyOtp'])
        ->middleware('throttle:10,1')
        ->name('verification.verify-otp');
    
    Route::post('/email/resend-otp', [AuthController::class, 'resendOtp'])
        ->middleware('throttle:3,1')
        ->name('verification.resend-otp');
    
    // GET fallback for browser refresh
    Route::get('/email/resend-otp', function () {
        return redirect()->route('verification.notice');
    });

    // Legacy route for backwards compatibility
    Route::post('/email/verification-notification', [AuthController::class, 'resendOtp'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Menu management
    Route::get('/menu', [AdminController::class, 'menuIndex'])->name('menu.index');
    Route::get('/menu/create', [AdminController::class, 'menuCreate'])->name('menu.create');
    Route::post('/menu', [AdminController::class, 'menuStore'])->name('menu.store');
    Route::get('/menu/{menu}', [AdminController::class, 'menuShow'])->name('menu.show');
    Route::get('/menu/{menu}/edit', [AdminController::class, 'menuEdit'])->name('menu.edit');
    Route::put('/menu/{menu}', [AdminController::class, 'menuUpdate'])->name('menu.update');
    Route::delete('/menu/{menu}', [AdminController::class, 'menuDestroy'])->name('menu.destroy');
    
    // Category management
    Route::get('/category', [AdminController::class, 'categoryIndex'])->name('category.index');
    Route::get('/category/create', [AdminController::class, 'categoryCreate'])->name('category.create');
    Route::post('/category', [AdminController::class, 'categoryStore'])->name('category.store');
    Route::get('/category/{category}/edit', [AdminController::class, 'categoryEdit'])->name('category.edit');
    Route::put('/category/{category}', [AdminController::class, 'categoryUpdate'])->name('category.update');
    Route::delete('/category/{category}', [AdminController::class, 'categoryDestroy'])->name('category.destroy');
    
    // Order management
    Route::get('/order', [AdminController::class, 'orderIndex'])->name('order.index');
    Route::get('/order/{order}', [AdminController::class, 'orderShow'])->name('order.show');
    Route::get('/order/{order}/shipping-label', [AdminController::class, 'orderShippingLabel'])->name('order.shipping-label');
    Route::get('/order/{order}/tracking', [AdminController::class, 'orderTracking'])->name('order.tracking');
    Route::post('/order/{order}/create-biteship', [AdminController::class, 'createBiteshipOrder'])->name('order.create-biteship');
    Route::put('/order/{order}', [AdminController::class, 'orderUpdate'])->name('order.update');
    Route::delete('/order/{order}', [AdminController::class, 'orderDestroy'])->name('order.destroy');
    
    // Message/Chat management
    Route::get('/message', [AdminController::class, 'messageIndex'])->name('message.index');
    Route::get('/message/chat/{user}', [AdminController::class, 'getCustomerChat'])->name('message.chat');
    Route::post('/message/reply', [AdminController::class, 'sendChatReply'])->name('message.reply');
    Route::get('/message/fetch/{user}', [AdminController::class, 'fetchChatMessages'])->name('message.fetch');
    Route::get('/message/{message}', [AdminController::class, 'messageShow'])->name('message.show');
    Route::delete('/message/clear/{user}', [AdminController::class, 'clearChat'])->name('message.clear');

    // Rute Laporan
    Route::get('/reports', [AdminController::class, 'reportIndex'])->name('report.index');
    Route::get('/reports/export', [AdminController::class, 'reportExport'])->name('report.export');

    // RUTE BARU: CRUD Pembelian
    Route::resource('/purchases', PurchaseController::class)->names('purchases');

    //Expense
    Route::get('/expense', [AdminController::class, 'expenseIndex'])->name('expense.index');
    Route::get('/expense/create', [AdminController::class, 'expenseCreate'])->name('expense.create');
    Route::post('/expense', [AdminController::class, 'expenseStore'])->name('expense.store');
    Route::delete('/expense/{expense}', [AdminController::class, 'expenseDestroy'])->name('expense.destroy');

    // 1. Rute Jurnal Umum
    Route::get('/journal', [AdminController::class, 'journalIndex'])->name('journal.index');
    Route::get('/journal/export', [AdminController::class, 'journalExport'])->name('journal.export');
    
    // 2. Rute Buku Besar
    Route::get('/ledger', [AdminController::class, 'ledgerIndex'])->name('ledger.index');
    Route::get('/ledger/export', [AdminController::class, 'ledgerExport'])->name('ledger.export');

    // 3. Rute Neraca Saldo
    Route::get('/trial-balance', [AdminController::class, 'trialBalanceIndex'])->name('trial_balance.index');
    Route::get('/trial-balance/export', [AdminController::class, 'trialBalanceExport'])->name('trial_balance.export');

    // 4. Rute Laporan Laba Rugi
    Route::get('/income-statement', [AdminController::class, 'incomeStatementIndex'])->name('income_statement.index');
    Route::get('/income-statement/export', [AdminController::class, 'incomeStatementExport'])->name('income_statement.export');

    // Chart of account
    Route::resource('/chart-of-accounts', ChartOfAccountController::class)->names('chart_of_accounts');
});

// Customer routes
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    
    // Menu browsing
    Route::get('/menu', [CustomerController::class, 'menuIndex'])->name('menu.index');
    Route::get('/menu/{menu}', [CustomerController::class, 'menuShow'])->name('menu.show');
    
    // Order routes
    Route::get('/order', [CustomerController::class, 'orderIndex'])->name('order.index'); // Halaman shopping cart
    Route::get('/order/create', [CustomerController::class, 'orderCreate'])->name('order.create'); // Halaman checkout
    Route::post('/order', [CustomerController::class, 'orderStore'])->name('order.store'); // Store order
    // API Wilayah & Ongkir
    Route::get('/provinces', [CustomerController::class, 'getProvinces'])->name('api.provinces');
    Route::get('/cities/{province}', [CustomerController::class, 'getCities'])->name('api.cities');
    Route::get('/search-destination', [CustomerController::class, 'searchDestination'])->name('api.search_destination');
    Route::post('/check-shipping', [CustomerController::class, 'checkShippingCost'])->name('api.shipping');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders'); // Riwayat pesanan
    Route::get('/order/{order}', [CustomerController::class, 'orderShow'])->name('order.show'); // Detail pesanan
    Route::post('/order/{order}/reorder', [CustomerController::class, 'orderReorder'])->name('order.reorder'); // Pesan lagi
    Route::post('/order/{order}/confirm-delivery', [CustomerController::class, 'confirmDelivery'])->name('order.confirm-delivery'); // Konfirmasi pesanan sampai
    Route::get('/order/{order}/tracking', [CustomerController::class, 'orderTracking'])->name('order.tracking'); // API tracking
     
    // Payment routes
    Route::get('/order/{order}/payment', [CustomerController::class, 'orderPayment'])->name('order.payment');
    Route::post('/order/{order}/payment/proof', [CustomerController::class, 'paymentProofUpload'])->name('payment.proof.upload');
     
    // Midtrans routes
    Route::get('/order/{order}/midtrans', [CustomerController::class, 'orderMidtrans'])->name('order.midtrans');
    Route::get('/midtrans/finish', [CustomerController::class, 'midtransFinish'])->name('midtrans.finish');
    Route::get('/midtrans/unfinish', [CustomerController::class, 'midtransUnfinish'])->name('midtrans.unfinish');
    Route::get('/midtrans/error', [CustomerController::class, 'midtransError'])->name('midtrans.error');
    
    // Message management (Legacy - redirects to chat)
    Route::get('/message', [CustomerController::class, 'messageIndex'])->name('message.index');
    Route::get('/message/json', [CustomerController::class, 'messageJson'])->name('message.json');
    Route::post('/message', [CustomerController::class, 'messageStore'])->name('message.store');
    Route::get('/message/{message}', [CustomerController::class, 'messageShow'])->name('message.show');
    Route::delete('/message/clear', [CustomerController::class, 'clearChat'])->name('message.clear');
    
    // Chat routes (New real-time chat)
    Route::get('/chat', [CustomerController::class, 'chatIndex'])->name('chat.index');
    Route::post('/chat/send', [CustomerController::class, 'chatSend'])->name('chat.send');
    Route::get('/chat/fetch', [CustomerController::class, 'chatFetch'])->name('chat.fetch');
    Route::post('/chat/clear', [CustomerController::class, 'chatClear'])->name('chat.clear');
});

Route::post('/midtrans/callback', [CustomerController::class, 'midtransCallback'])->name('midtrans.callback');