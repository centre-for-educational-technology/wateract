<?php

use Illuminate\Support\Facades\Route;
use App\Models\Spring;
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
    return view('springs.index', ['springs' => Spring::all()]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
Route::get('/logout', function () {
    Auth::logout();
    return view('springs.index', ['springs' => Spring::all()]);
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::resource('springs', SpringController::class);
Route::resource('spring_observation_data', SpringObservationDataController::class);

Route::get('springs_json', function () {
    $springs = Spring::all();
    $features = array();
    foreach( $springs as $spring) {
        $coordinates = array($spring->longitude, $spring->latitude);
        $title = $spring->title;
        if (!$title) {
            $title = __('springs.unnamed');
        }
        $features[] = array(
            'type' => 'Feature',
            'geometry' => array('type' => 'Point', 'coordinates' => $coordinates),
            'properties' => array(
                'id' => $spring->id,
                'title' => $title,
                'status' => $spring->status,
            ),
        );
    }
    $new_data = array(
        'type' => 'FeatureCollection',
        'features' => $features,
    );
    return json_encode($new_data, JSON_PRETTY_PRINT);
});
