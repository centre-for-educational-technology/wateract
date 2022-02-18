<?php

use App\Http\Controllers\CsvFileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SpringFeedbackController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\App;
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

// main menu routes
Route::get('/', function () {
    $base_url = env('APP_URL', '');
    return Inertia\Inertia::render('Springs/LandingPage')
        ->withViewData([
        'og_title' => env( 'APP_NAME', ''),
        'og_image' => $base_url . '/images/springs-slogan.jpg',
        'og_url' => $base_url
    ]);
});

Route::get('/about-springs', function () {
    return Inertia\Inertia::render('StaticPages/AboutSprings', [
        'currentRouteName' => 'about-springs'
    ]);
});
Route::get('/instructions', function () {
    return Inertia\Inertia::render('StaticPages/Instructions', [
        'currentRouteName' => 'instructions'
    ]);
});
Route::get('/about-wateract', function () {
    return Inertia\Inertia::render('StaticPages/AboutWateract', [
        'currentRouteName' => 'about-wateract'
    ]);
});
Route::get('/privacy-policy', function () {
    return Inertia\Inertia::render('StaticPages/PrivacyPolicy', [
        'currentRouteName' => 'privacy-policy'
    ]);
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $springs = Spring::where('user_id', Auth::id())->get();
    return Inertia\Inertia::render('Dashboard', ['springs' => $springs]);
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/admin', function () {
    return Inertia\Inertia::render('Admin', [
        'springs_to_update' => Spring::whereNull('county')->whereNull('settlement')->get(),
    ]);
})->name('admin');

Route::post('/admin/csvfile', [CsvFileController::class, 'update'])
    ->middleware(['can:administrate']);

Route::get('admin/exportSprings', [CsvFileController::class, 'exportSprings'])
    ->middleware(['can:view users']);
Route::get('admin/exportObservations', [CsvFileController::class, 'exportObservations'])
    ->middleware(['can:view users']);

Route::resource('admin/users', UserController::class)
    ->middleware(['can:view users']);;

Route::post('/admin/updateSpringAddress', [SpringController::class, 'updateSpringAddress'])
    ->middleware(['can:administrate']);;

/*Route::group(['middleware' => ['auth']], function() {
    Route::resource('admin/users', UserController::class);
});*/

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::resource('news', NewsController::class);
Route::resource('springs', SpringController::class);
Route::resource('photos', PhotoController::class);
Route::resource('spring_feedback', SpringFeedbackController::class);
Route::resource('springs.feedback', SpringFeedbackController::class);
Route::resource('springs.observations', ObservationController::class);
Route::resource('observations', ObservationController::class);
Route::resource('springs.analyses', MeasurementController::class, [ 'parameters' => [
    'analyses' => 'measurement'
]]);
Route::resource('measurements', MeasurementController::class);
Route::get('/springs/{spring_id}/feedbackview', [SpringFeedbackController::class, 'view']);
Route::get('/springsforreview', [SpringController::class, 'springsForReview']);
Route::get('/userspringsview/{user_id}', [UserController::class, 'userSprings']);
Route::get('/userobservationsview/{user_id}', [UserController::class, 'userObservations']);
Route::get('/usermeasurementsview/{user_id}', [UserController::class, 'userMeasurements']);
Route::get('/getSprings', [SpringController::class, 'getSprings']);
Route::get('/getSpringInfo', [SpringController::class, 'getSpringInfo']);
Route::get('/getObservations', [ObservationController::class, 'getObservations']);
Route::get('/getMeasurements', [MeasurementController::class, 'getMeasurements']);
Route::get('/getStatistics', [StatisticsController::class, 'getStatistics']);
Route::get('/getNews', [NewsController::class, 'getNews']);
