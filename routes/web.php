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
});

// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     Route::get('/family', [PersonController::class, 'index'])->name('family.index');
//     Route::get('/family/data', [PersonController::class, 'data'])->name('family.data');
//     Route::post('/person', [PersonController::class, 'storePerson'])->name('persons.store');
//     Route::post('/marriages', [PersonController::class, 'storeMarriage'])->name('marriages.store');
//     Route::post('/relationships', [PersonController::class, 'addParentChild'])->name('relationships.store');

//     // Resource routes
//     Route::resource('persons', PersonController::class);
//     Route::resource('relationships', RelationshipController::class);
// });

Route::middleware(['auth', 'verified'])->group(function () {
    // People routes
    Route::resource('people', PersonController::class);

    // Relationship routes
    Route::get('relationships/create', [RelationshipController::class, 'create'])->name('relationships.create');
    Route::post('relationships', [RelationshipController::class, 'store'])->name('relationships.store');
    Route::delete('relationships/{relationship}', [RelationshipController::class, 'destroy'])->name('relationships.destroy');
});