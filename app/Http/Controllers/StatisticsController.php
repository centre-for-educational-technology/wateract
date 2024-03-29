<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\Observation;
use App\Models\Photo;
use App\Models\Spring;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    public function getStatistics() {
        $number_of_springs = Spring::where('unlisted', 0)->whereIn('status', ['submitted', 'confirmed'])->count();
        $number_of_observations = Observation::where('status', 'submitted')->count();
        $number_of_measurements = Measurement::where('status', 'submitted')->count();
        $number_of_springs_7_days = Spring::whereIn('status', ['submitted', 'confirmed'])
            ->whereBetween('created_at', [
                Carbon::now()->subdays(7)->format('Y-m-d H:i'),
                Carbon::now()->format('Y-m-d H:i')
            ])->count();
        $number_of_observations_7_days = Observation::whereIn('status', ['submitted', 'confirmed'])
            ->whereBetween('created_at', [
                Carbon::now()->subdays(7)->format('Y-m-d H:i'),
                Carbon::now()->format('Y-m-d H:i')
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

        $users_ids_for_statistics = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['super-admin', 'admin', 'editor']);
        })->with('roles')->pluck('id');;

        return DB::table('springs')
            ->select('user_id', 'users.name', DB::raw('count(*) as total'))
            ->join('users', 'springs.user_id', '=', 'users.id')
            ->whereIn('user_id', $users_ids_for_statistics)
            ->groupBy('user_id', 'users.name')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get();
    }

    public function mostActiveObservationUsers() {

        $users_ids_for_statistics = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['super-admin', 'admin', 'editor']);
        })->with('roles')->pluck('id');;

        return DB::table('observations')
            ->select('user_id', 'users.name', DB::raw('count(*) as total'))
            ->join('users', 'observations.user_id', '=', 'users.id')
            ->whereIn('user_id', $users_ids_for_statistics)
            ->groupBy('user_id', 'users.name')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get();
    }

}
