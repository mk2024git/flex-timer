<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;

// ホームページのルートを設定
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/task/create', [TaskController::class, 'create'])->name('task.create');
Route::get('/task/index', [TaskController::class, 'index'])->name('task.index');
Route::post('/task/destroy', [TaskController::class, 'destroy'])->name('task.destroy');

// デフォルトのルートをホームページにリダイレクト
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
