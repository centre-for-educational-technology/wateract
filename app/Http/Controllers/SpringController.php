<?php

namespace App\Http\Controllers;

use App\Models\Spring;
use Illuminate\Http\Request;

class SpringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $springs = Spring::latest()->paginate(5);
        return view('springs.index',compact('springs'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
            'title' => 'required',
        ]);
        //$references = json_encode($request);

        Spring::create($request->all());
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
            'title' => 'required',
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
