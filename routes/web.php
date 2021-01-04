<?php

use App\Http\Controllers\CsvFileController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SpringFeedbackController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Spring;
use App\Http\Controllers\SpringController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\MeasurementController;

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
    $springs = Spring::whereIn('status', ['submitted', 'confirmed'])->get();
    $featured_springs = Spring::where('featured', '1')->with('photos')->inRandomOrder()->limit(4)->get();
    $newest_springs = Spring::whereIn('status', ['submitted', 'confirmed'])->with('photos')->orderBy('created_at', 'desc')->limit(4)->get();
    return Inertia\Inertia::render('Springs/Index', [
        'springs' => $springs,
        'featured_springs' => $featured_springs,
        'newest_springs' => $newest_springs,
        'classifications' => (new App\Http\Controllers\SpringController)->getClassifications(),
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $springs = Spring::where('user_id', Auth::id())->get();
    return Inertia\Inertia::render('Dashboard', ['springs' => $springs]);
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/admin', function () {
    return Inertia\Inertia::render('Admin');
})->name('admin');

Route::post('/admin/csvfile', [CsvFileController::class, 'update'])
    ->middleware(['auth']);

/*Route::get('/springs', function () {
    return Inertia\Inertia::render('Springs/Index');
})->name('springs');
Route::get('/springs/create', function () {
    return Inertia\Inertia::render('Springs/Create');
})->name('springs');*/

Route::resource('admin/users', UserController::class);

/*Route::group(['middleware' => ['auth']], function() {
    Route::resource('admin/users', UserController::class);
});*/

/*Route::get('/logout', function () {
    Auth::logout();
    return view('springs.index', ['springs' => Spring::all()]);
});*/

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::resource('springs', SpringController::class);
Route::resource('photos', PhotoController::class);
Route::resource('spring_feedback', SpringFeedbackController::class);
Route::resource('springs.feedback', SpringFeedbackController::class);
Route::resource('springs.observations', ObservationController::class);
Route::resource('observations', ObservationController::class);
Route::resource('springs.measurements', MeasurementController::class);
Route::resource('measurements', MeasurementController::class);
Route::get('/springs/{spring_id}/feedbackview', [SpringFeedbackController::class, 'view']);
Route::get('/myspringsview', [UserController::class, 'mySprings']);
Route::get('/getSprings', [SpringController::class, 'getSprings']);

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
