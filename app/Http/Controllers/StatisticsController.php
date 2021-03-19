<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\Observation;
use App\Models\Photo;
use App\Models\Spring;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    public function getStatistics() {
        $number_of_springs = Spring::where('unlisted', 0)->whereIn('status', ['submitted', 'confirmed'])->count();
        $number_of_observations = Observation::where('status', 'submitted')->count();
        $number_of_measurements = Measurement::where('status', 'submitted')->count();
        $number_of_springs_7_days = Spring::whereIn('status', ['submitted', 'confirmed'])
            ->whereBetween('created_at', [
                Carbon::now()->subdays(7)->format('Y-m-d'),
                Carbon::now()->subday()->format('Y-m-d')
            ])->count();
        $number_of_observations_7_days = Observation::whereIn('status', ['submitted', 'confirmed'])
            ->whereBetween('created_at', [
                Carbon::now()->subdays(7)->format('Y-m-d'),
                Carbon::now()->subday()->format('Y-m-d')
            ])->count();
        $statistics = [
            'number_of_springs' => $number_of_springs,
            'number_of_observations' => $number_of_observations,
            'number_of_measurements' => $number_of_measurements,
            'number_of_photos' => $this->getPhotosCount(),
            'number_of_springs_7_days' => $number_of_springs_7_days,
            'number_of_observations_7_days' => $number_of_observations_7_days,
            'springs_active_users' => $this->mostActiveSpringUsers(),
            'observations_active_users' => $this->mostActiveObservationUsers(),
            'photo' => $this->getRandomSpringPhoto(),
        ];
        return response()->json($statistics);
    }

    public function getPhotosCount() {
        $public_spring_ids = Spring::where('unlisted', 0)->whereIn('status', ['submitted', 'confirmed'])->pluck('id');
        $public_spring_photos_count = Photo::where('observation_id', null)
            ->whereIn('spring_id', $public_spring_ids)->count();

        $observation_ids = Observation::where('status', 'submitted')->pluck('id');
        $observation_photos_count = Photo::whereIn('observation_id', $observation_ids)->count();

        return $public_spring_photos_count + $observation_photos_count;
    }

    public function mostActiveSpringUsers() {
        return DB::table('springs')
            ->select('user_id', 'users.name', DB::raw('count(*) as total'))
            ->join('users', 'springs.user_id', '=', 'users.id')
            ->where('user_id', '!=', null)
            ->groupBy('user_id')
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->get();
    }

    public function mostActiveObservationUsers() {
        return DB::table('observations')
            ->select('user_id', 'users.name', DB::raw('count(*) as total'))
            ->join('users', 'observations.user_id', '=', 'users.id')
            ->groupBy('user_id')
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->get();
    }

    public function getRandomSpringPhoto() {
        $public_spring_ids = Spring::where('unlisted', 0)->whereIn('status', ['submitted', 'confirmed'])->pluck('id');
        $public_spring_photos = Photo::where('observation_id', null)
            ->whereIn('spring_id', $public_spring_ids)
            ->join('springs', 'photos.spring_id', '=', 'springs.id')
            ->get();

        $observation_ids = Observation::where('status', 'submitted')->pluck('id');
        $observation_photos = Photo::whereIn('observation_id', $observation_ids)
            ->join('springs', 'photos.spring_id', '=', 'springs.id')
            ->get();

        $all_spring_photos = $public_spring_photos->merge($observation_photos);
        return $all_spring_photos->random(1);

    }
}
