<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public projects listing (accessible to all)
Route::get('/projects', [SiteController::class, 'projects'])->name('projects');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Pages publiques: about, skills, contact
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/skills', function () {
    return view('skills');
})->name('skills');

Route::get('/contact', [SiteController::class, 'contact'])->name('contact'); 

// Admin routes: seules les vues sous /admin/* sont protégées
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class)->except(['show']);
});

Route::get('/cv', function () {
    return response()->file(public_path('cv_amour.pdf'));
})->name('cv');

// ✅ Route de debug unique
Route::get('/_debug/cloudinary', function () {
    return response()->json([
        'CLOUDINARY_URL'        => env('CLOUDINARY_URL'),
        'CLOUDINARY_API_KEY'    => env('CLOUDINARY_API_KEY'),
        'CLOUDINARY_API_SECRET' => env('CLOUDINARY_API_SECRET'),
        'CLOUDINARY_CLOUD_NAME' => env('CLOUDINARY_CLOUD_NAME'),
        'APP_ENV'               => env('APP_ENV'),
    ]);
});
