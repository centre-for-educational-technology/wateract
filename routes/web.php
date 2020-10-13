<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpringController;
use App\Http\Controllers\SpringObservationDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $springs = \App\Models\Spring::all();
    return view('springs.index', ['springs' => $springs]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
Route::get('/logout', function () {
    Auth::logout();
    return view('springs.index', ['springs' => \App\Models\Spring::all()]);
});


//App::setLocale('et');

Route::resource('springs', SpringController::class);
Route::resource('spring_observation_data', SpringObservationDataController::class);
