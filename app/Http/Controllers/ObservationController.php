<?php

namespace App\Http\Controllers;

use App\Models\Spring;
use App\Models\Observation;
use App\Models\ObservationFieldValue;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param string $spring_code
     * @return Response
     */
    public function index(string $spring_code)
    {
        $spring = Spring::where('code', $spring_code)->first();
        $observations = Observation::where('spring_id', $spring->id)->with('user')->get();
        return Inertia::render('Observations/Index', [
            'spring' => $spring,
            'observations' => $observations
        ]);
    }

    public function getTasteOptions() {
        return [
            array( 'id' => 'fine', 'name' => 'springs.taste_options.fine'),
            array('id' => 'flat', 'name' => 'springs.taste_options.flat'),
            array('id' => 'metallic', 'name' => 'springs.taste_options.metallic'),
            array('id' => 'earthy', 'name' => 'springs.taste_options.earthy'),
            array('id' => 'rotten', 'name' => 'springs.taste_options.rotten'),
            array('id' => 'salty', 'name' => 'springs.taste_options.salty'),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $spring_code
     * @return Response
     */
    public function create(string $spring_code)
    {
        $spring = Spring::where('code', $spring_code)->with('observations')->first();
        if (Auth::user()) {
            $observation_fields = \App\Models\ModelField::where('model', 'observation')->where('visible', 1)->orderBy('position')->get();
            return Inertia::render('Observations/Create', [
                'spring' => $spring,
                'observation_fields' => $observation_fields,
                'taste_options' => ObservationController::getTasteOptions()
            ]);
        }
        return Inertia::render('Observations/Index', ['spring' => $spring]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'measurement_time' => 'required',
        ])->validateWithBag('addObservation');

        $request['user_id'] = Auth::id();
        $observation = Observation::create($request->all());
        ObservationController::saveFieldValues($observation, $request['observation_values']);

        $spring = Spring::find($request['spring_id']);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success','Observation added successfully.');
    }

    public function saveFieldValues($observation, $values) {
        foreach ($values as $field_data) {
            $field_id = $field_data['id'];
            $value = isset($field_data['value']) ? $field_data['value'] : false;
            if ($value) {
                $field_value = new ObservationFieldValue();
                $field_value->observation_id = $observation->id;
                $field_value->field_id = $field_id;
                $field_value->value = $value;
                $field_value->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $spring_code
     * @param int $observation_id
     * @return JsonResponse
     */
    public function show(string $spring_code, int $observation_id)
    {
        $observation = Observation::find($observation_id);
        return response()->json($observation->getFieldValues());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $spring_id
     * @param Observation $observation
     * @return Response
     */
    public function edit(string $spring_code, Observation $observation)
    {
        $spring = Spring::where('code', $spring_code)->with('observations')->first();
        if (Auth::user()) {
            $observation_fields = \App\Models\ModelField::where('model', 'observation')->where('visible', 1)->orderBy('position')->get();
            return Inertia::render('Observations/Edit', [
                'spring' => $spring,
                'observation' => $observation,
                'observation_fields' => $observation_fields,
                'taste_options' => ObservationController::getTasteOptions()
            ]);
        }
        return Inertia::render('Observations/Index', ['spring' => $spring]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Observation $observation
     * @return RedirectResponse
     */
    public function update(Request $request, Observation $observation)
    {
        Validator::make($request->all(), [
            'measurement_time' => 'required',
        ])->validateWithBag('editObservation');
        $observation->update($request->all());
        $spring = Spring::find($observation->spring_id);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success','Observation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Observation $observation
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Observation $observation)
    {
        $observation->delete();
        $spring = Spring::find($observation->spring_id);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success','Observation deleted successfully.');
    }
}
