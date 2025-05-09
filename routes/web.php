<?php


use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RelationshipController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Redirect root to /keturunan
Route::get('/', function () {
    return redirect()->route('public.family-tree.index');
    // Atau langsung ke path:
    // return redirect('/keturunan');
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
    Route::get('/relationship-check', [RelationshipController::class, 'index'])->name('relationship.check.index');
    Route::get('relationships/create', [RelationshipController::class, 'create'])->name('relationships.create');
    Route::post('relationships', [RelationshipController::class, 'store'])->name('relationships.store');
    Route::delete('relationships/{relationship}', [RelationshipController::class, 'destroy'])->name('relationships.destroy');
    Route::post('/relationship-check', [RelationshipController::class, 'check'])->name('relationship.check');


    // API route untuk select component
    Route::get('/api/people', function () {
        return Inertia::render('API/People', [
            'people' => \App\Models\Person::orderBy('name')->paginate(20),
        ]);
    })->name('api.people.index');
});

// Public family tree routes
Route::prefix('keturunan')->group(function () {
    // Main page with search
    Route::get('/', [\App\Http\Controllers\Umum\FamilyTreeController::class, 'index'])
        ->name('public.family-tree.index');

    // Person profile page
    Route::get('/{person}', [\App\Http\Controllers\Umum\FamilyTreeController::class, 'show'])
        ->name('public.family-tree.show');
});
