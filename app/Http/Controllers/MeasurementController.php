<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\MeasurementFieldValue;
use App\Models\Spring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $spring_id
     * @return \Illuminate\Http\Response
     */
    public function index(int $spring_id)
    {
        $spring = Spring::find($spring_id);
        return view('measurements.index', compact('spring'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $spring_id
     * @return \Illuminate\Http\Response
     */
    public function create(int $spring_id)
    {
        $spring = Spring::find($spring_id);
        return view('measurements.create', compact('spring'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $spring_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $spring_id)
    {
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
        foreach ($request['measurement_values'] as $field_id=>$value) {
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
    public function show(Measurement $measurement)
    {
        //
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
        foreach ($request['measurement_values'] as $field_id=>$value) {
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
