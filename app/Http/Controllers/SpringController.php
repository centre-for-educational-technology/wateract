<?php

namespace App\Http\Controllers;

use App\Models\Spring;
use Illuminate\Http\Request;
use function Psy\debug;
use Illuminate\Support\Facades\Auth;

class SpringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $springs = Spring::all();
        return view('springs.index',compact('springs'));
            //->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('springs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'description' => 'required'
        ]);

        $request['user_id'] = Auth::id();
        Spring::create($request->all());
        // save references
        //$reference = new Reference();
        //Reference::create($request);
        //print_r($request['spring_references']);
        //foreach ($request['spring_references'] as $reference_info) {
            //SpringReference::create($reference_info);
        //}

        return redirect()->route('springs.index')
            ->with('success','Spring created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spring  $spring
     * @return \Illuminate\Http\Response
     */
    public function show(Spring $spring)
    {
        return view('springs.show', compact('spring'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spring  $spring
     * @return \Illuminate\Http\Response
     */
    public function edit(Spring $spring)
    {
        return view('springs.edit', compact('spring'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spring  $spring
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spring $spring)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'description' => 'required',
        ]);
        $spring->update($request->all());
        return redirect()->route('springs.index')
            ->with('success','Spring updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Spring $spring
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Spring $spring)
    {
        $spring->delete();

        return redirect()->route('springs.index')
            ->with('success', 'Spring deleted successfully');
    }
}
