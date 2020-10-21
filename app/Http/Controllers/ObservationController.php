<?php

namespace App\Http\Controllers;

use App\Models\Spring;
use App\Models\Observation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $spring_id
     * @return \Illuminate\Http\Response
     */
    public function index(int $spring_id)
    {
        $spring = Spring::find($spring_id);
        return view('observations.index', compact('spring'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $spring_id
     * @return \Illuminate\Http\Response
     */
    public function create(int $spring_id)
    {
        $spring = Spring::find($spring_id);
        return view('observations.create', compact('spring'));
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
            'measurement_time' => 'required',
        ]);
        $request['user_id'] = Auth::id();
        $request['spring_id'] = $spring_id;
        Observation::create($request->all());
        $spring = Spring::find($spring_id);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success','Observation added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Observation  $observation
     * @return \Illuminate\Http\Response
     */
    public function show(Observation $observation)
    {
        return view('observations.show', compact('observation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $spring_id
     * @param \App\Models\Observation $observation
     * @return \Illuminate\Http\Response
     */
    public function edit(int $spring_id, Observation $observation)
    {
        return view('observations.edit', compact('observation') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Observation  $observation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Observation $observation)
    {
        $request->validate([
            'measurement_time' => 'required',
        ]);
        $observation->update($request->all());
        $spring = Spring::find($observation->spring_id);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success','Observation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Observation $observation
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Observation $observation)
    {
        $observation->delete();
        $spring = Spring::find($observation->spring_id);
        return redirect()->route('springs.observations.index', compact('spring'))
            ->with('success','Observation deleted successfully.');
    }
}
