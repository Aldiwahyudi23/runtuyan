<?php


use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RelationshipController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    // People routes
    Route::resource('people', PersonController::class);

    // Relationship routes
    Route::get('relationships/create', [RelationshipController::class, 'create'])->name('relationships.create');
    Route::post('relationships', [RelationshipController::class, 'store'])->name('relationships.store');
    Route::delete('relationships/{relationship}', [RelationshipController::class, 'destroy'])->name('relationships.destroy');
});