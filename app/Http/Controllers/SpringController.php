<?php

namespace App\Http\Controllers;

use App\Models\Spring;
use App\Models\SpringDatabaseLink;
use Illuminate\Http\Request;
use function Psy\debug;
use Illuminate\Support\Facades\Auth;
use App\Models\SpringReference;

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
        $spring = Spring::create($request->all());
        // save references
        //$reference = new Reference();
        //Reference::create($request);
        //print_r($request['spring_references']);
        //var_dump($request['spring_references']);exit;
        foreach ($request['spring_references'] as $reference_info) {
            //var_dump($reference_info);exit;
            $spring_reference = new SpringReference();
            if ($reference_info['url']) {
                $spring_reference->spring_id = $spring->id;
                $spring_reference->url = $reference_info['url'];
                if ($reference_info['url_title']) {
                    $spring_reference->url_title = $reference_info['url_title'];
                }
                $spring_reference->save();
            }
        }
        // save database links
        foreach ($request['spring_databases'] as $database_info) {
            if (empty($database_info['database_name']) && empty($database_info['code'])
                && empty($database_info['spring_name']) && empty($database_info['url'])) {
                continue;
            }
            $spring_database_link = new SpringDatabaseLink();
            $spring_database_link->spring_id = $spring->id;
            $spring_database_link->database_name = $database_info['database_name'];
            $spring_database_link->code = $database_info['code'];
            $spring_database_link->spring_name = $database_info['spring_name'];
            $spring_database_link->url = $database_info['url'];
            $spring_database_link->save();
        }

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

        foreach ($request['spring_references'] as $reference_info) {
            if (isset($reference_info['id'])) {
                //$spring_reference = SpringReference::where('id' , '=' , $reference_info['id'] )->get();
                $spring_reference = SpringReference::find($reference_info['id']);
                if ($reference_info['url']) {
                    $spring_reference->url = $reference_info['url'];
                    if ($reference_info['url_title']) {
                        $spring_reference->url_title = $reference_info['url_title'];
                    }
                    $spring_reference->save();
                }
            } else {
                //var_dump($reference_info);exit;
                $spring_reference = new SpringReference();
                if ($reference_info['url']) {
                    $spring_reference->spring_id = $spring->id;
                    $spring_reference->url = $reference_info['url'];
                    if ($reference_info['url_title']) {
                        $spring_reference->url_title = $reference_info['url_title'];
                    }
                    $spring_reference->save();
                }
            }

        }

        return redirect()->route('springs.show', compact('spring'))
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
