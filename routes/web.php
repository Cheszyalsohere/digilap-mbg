<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminAnalisisController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\FeedbackController;
use App\Http\Controllers\Siswa\MenuController as SiswaMenuController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Sppg\MenuController as SppgMenuController;
use App\Http\Controllers\Sppg\SppgDashboardController;
use App\Http\Controllers\Sppg\SppgLaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'sppg'  => redirect()->route('sppg.dashboard'),
            default => redirect()->route('siswa.dashboard'),
        };
    }
    return redirect()->route('login');
});

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:siswa'])->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [SiswaMenuController::class, 'index'])->name('menu');
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

Route::middleware(['auth', 'role:sppg'])->prefix('sppg')->name('sppg.')->group(function () {
    Route::get('/dashboard', [SppgDashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu/create', [SppgMenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [SppgMenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{menu}/edit', [SppgMenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{menu}', [SppgMenuController::class, 'update'])->name('menu.update');
    Route::get('/laporan', [SppgLaporanController::class, 'index'])->name('laporan');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analisis', [AdminAnalisisController::class, 'index'])->name('analisis');
    Route::get('/export', [AdminAnalisisController::class, 'export'])->name('export');
    Route::resource('users', AdminUserController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
