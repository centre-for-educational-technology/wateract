<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\MeasurementFieldValue;
use App\Models\Spring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $spring_code
     * @return \Inertia\Response
     */
    public function index(string $spring_code)
    {
        $spring = Spring::where('code', $spring_code)->with('measurements')->first();
        return Inertia::render('Measurements/Index', ['spring' => $spring]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $spring_code
     * @return \Inertia\Response
     */
    public function create(string $spring_code)
    {
        $spring = Spring::where('code', $spring_code)->with('measurements')->first();
        if (Auth::user()) {
            $fields = \App\Models\ModelField::where('model', 'measurement')->where('visible', 1)->orderBy('position')->get();
            return Inertia::render('Measurements/Create', ['spring' => $spring, 'measurement_fields' => $fields]);
        }
        return Inertia::render('Measurements/Index', ['spring' => $spring]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $spring_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $spring_id = $request['spring_id'];
        $request->validate([
            'analysis_time' => 'required'
        ]);
        $measurement_values = [
            'user_id' => Auth::id(),
            'spring_id' => $spring_id,
            'analysis_time' => $request['analysis_time']
        ];
        $request['user_id'] = Auth::id();
        $measurement = Measurement::create($measurement_values);
        // save measurement info
        //var_dump($request['measurement_values']);
        foreach ($request['measurement_values'] as $field_data) {
            $field_id = $field_data['id'];

            $value = isset($field_data['value']) ? $field_data['value'] : false;

            if ($value) {
                $measurement_value = new MeasurementFieldValue();
                $measurement_value->measurement_id = $measurement->id;
                $measurement_value->field_id = $field_id;
                $measurement_value->value = $value;
                $measurement_value->save();
            }
        }
        $spring = Spring::find($spring_id);
        return redirect()->route('springs.measurements.index', compact('spring'))
            ->with('success','Measurement added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function show(string $spring_code, int $measurement_id)
    {
        $measurement = Measurement::find($measurement_id);
        return response()->json($measurement->getFieldValues());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function edit(int $spring_id, Measurement $measurement)
    {
        return view('measurements.edit', compact('measurement') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Measurement $measurement)
    {
        $request->validate([
            'analysis_time' => 'required',
        ]);
        $measurement_values = [
            'analysis_time' => $request['analysis_time']
        ];
        $measurement->update($measurement_values);
        // save measurement info
        foreach ($request['measurement_values'] as $field_data) {
            $field_id = $field_data->id;
            $value = $field_data->value;
            $measurement_value = MeasurementFieldValue::where('field_id', $field_id)
                ->where('measurement_id', $measurement->id)
                ->first();
            if ($measurement_value) {
                if ($value) {
                    $measurement_value->value = $value;
                    $measurement_value->save();
                } else {
                    $measurement_value->delete();
                }
            } else {
                if ($value) {
                    $measurement_value = new MeasurementFieldValue();
                    $measurement_value->measurement_id = $measurement->id;
                    $measurement_value->field_id = $field_id;
                    $measurement_value->value = $value;
                    $measurement_value->save();
                }
            }
        }
        $spring = Spring::find($measurement->spring_id);
        return redirect()->route('springs.measurements.index', compact('spring'))
            ->with('success','Measurement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Measurement $measurement)
    {
        //
    }
}
