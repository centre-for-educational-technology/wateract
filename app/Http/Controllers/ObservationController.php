<?php

namespace App\Http\Controllers;

use App\Models\ModelField;
use App\Models\Photo;
use App\Models\Spring;
use App\Models\Observation;
use App\Models\ObservationFieldValue;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $observations = Observation::where('spring_id', $spring->id)
            ->where('status', 'submitted')
            ->with('user')->with('photos')->get();
        return Inertia::render('Observations/Index', [
            'spring' => $spring,
            'observations' => $observations,
            'can_edit' => $spring->canEdit()
        ]);
    }

    public function getTasteOptions() {
        return [
            array( 'id' => 'fine', 'name' => 'springs.taste_options.fine'),
            array('id' => 'metallic', 'name' => 'springs.taste_options.metallic'),
            array('id' => 'earthy', 'name' => 'springs.taste_options.earthy'),
            array('id' => 'rotten', 'name' => 'springs.taste_options.rotten'),
            array('id' => 'sweet', 'name' => 'springs.taste_options.sweet'),
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
     * @throws Exception
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'measurement_time' => 'required',
        ])->validateWithBag('addObservation');
        $date = new DateTime($request['measurement_time']);
        $request['measurement_time'] = $date->format('Y-m-d H:i');
        $request['user_id'] = Auth::id();
        $observation = Observation::create($request->all());
        ObservationController::saveFieldValues($observation, $request['observation_values']);

        if (!empty($request['photo_ids'])) {
            foreach($request['photo_ids'] as $photo_id) {
                $photo = Photo::where('id', $photo_id)->first();
                if ($photo) {
                    $photo->spring_id = $request['spring_id'];
                    $photo->observation_id = $observation->id;
                    $photo->save();
                }
            }
        }

        if ($observation->status === "draft") {
            return redirect()->route('dashboard')
                ->with('success', __('springs.messages.observation_added'));
        }

        $spring = Spring::find($request['spring_id']);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success', __('springs.messages.observation_added'));
    }

    public function saveFieldValues($observation, $values) {
        foreach ($values as $field_data) {
            $field_id = $field_data['id'];
            $value = isset($field_data['value']) ? $field_data['value'] : false;
            if ($value) {
                $field_value = new ObservationFieldValue();
                $field_value->observation_id = $observation->id;
                $field_value->field_id = $field_data['id'];
                $field_value->value = $value;
                $field_value->save();
            }
        }
    }

    public function updateFieldValues($observation, $values) {
        foreach ($values as $field_data) {
            $field_id = $field_data['id'];
            $value = isset($field_data['value']) ? $field_data['value'] : false;
            // check if exists
            $field_value = ObservationFieldValue::where('observation_id', $observation->id)
                ->where('field_id', $field_id)->first();
            if ($value) {
                if ( !$field_value) {
                    $field_value = new ObservationFieldValue();
                    $field_value->observation_id = $observation->id;
                    $field_value->field_id = $field_id;
                }
                $field_value->value = $value;
                $field_value->save();
            } else if ($field_value && !$value) {
                $field_value->delete();
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
     * @param string $spring_code
     * @param Observation $observation
     * @return Response
     */
    public function edit(string $spring_code, Observation $observation)
    {
        $this->authorize('update', $observation);

        $spring = Spring::where('code', $spring_code)->with('observations')->first();
        if (Auth::user()) {
            $observation = Observation::where('id', $observation->id)->with('photos')->first();
            $observation_fields = ModelField::where('model', 'observation')->where('visible', 1)->orderBy('position')->get();
            foreach ( $observation_fields as $field ) {
                $field_value = ObservationFieldValue::where('observation_id', $observation->id)->where('field_id', $field['id'])->first();
                if ($field_value) {
                    $field['value'] = $field_value->value;
                }
            }
            return Inertia::render('Observations/Edit', [
                'spring' => $spring,
                'observation' => $observation,
                'observation_fields' => $observation_fields,
                'taste_options' => ObservationController::getTasteOptions()
            ]);
        }
        return ObservationController::index($spring_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Observation $observation
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, Observation $observation)
    {
        $this->authorize('update', $observation);

        Validator::make($request->all(), [
            'measurement_time' => 'required',
        ])->validateWithBag('editObservation');

        $date = new DateTime($request['measurement_time']);
        $request['measurement_time'] = $date->format('Y-m-d H:i');
        $observation->update($request->all());

        ObservationController::updateFieldValues($observation, $request['observation_values']);

        $spring = Spring::find($observation->spring_id);

        (new PhotoController)->savePhotos($request['photos_to_add'], $spring->id, $observation->id);

        (new PhotoController)->deletePhotos($request['photos_to_delete']);

        if ($observation->status === "draft") {
            return redirect()->route('dashboard')
                ->with('success', __('springs.messages.observation_updated'));
        }
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success', __('springs.messages.observation_updated'));

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
        $this->authorize('delete', $observation);

        // delete observation photos
        $photos = Photo::where('observation_id', $observation->id)->get();
        foreach($photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            Storage::disk('public')->delete($photo->thumbnail);
            $photo->delete();
        }

        $spring = Spring::find($observation->spring_id);
        // deletes observation, also related database relations (observation fields values)
        $observation->delete();

        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success', __('springs.messages.observation_deleted'));
    }

    public function getObservations(Request $request)
    {
        $observations = Observation::where('status', 'submitted')
            ->orderBy($request['order_by'], 'desc')
            ->with('spring')
            ->with('photos')
            ->with('user');
        return $observations->paginate(10);
    }
}
