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


Route::get('/test-cloudinary', function () {
    return CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
        'https://res.cloudinary.com/demo/image/upload/sample.jpg'
    )->getSecurePath();
});
