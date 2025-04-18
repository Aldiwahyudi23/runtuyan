<?php

use App\Http\Controllers\RelationshipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Endpoint untuk mengambil data pasangan
Route::get('/people/{person}/spouses', [RelationshipController::class, 'getSpouses'])
    ->name('api.people.spouses');