<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

Route::get('/', function () {
    if (auth()->guard('web')->check()) {
        $user = auth()->guard('web')->user();

        // Nếu là ADMIN hoặc STAFF thì vào admin panel
        if (in_array($user->role, ['ADMIN', 'STAFF'])) {
            return redirect()->route('admin.dashboard');
        }

        // Nếu là CUSTOMER thì vào trang đặt vé
        return redirect()->route('booking.index');
    }
    return redirect()->route('auth.login.form');
})->name('home');

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});