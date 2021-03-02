<?php

namespace App\Http\Controllers;

use App\Models\Spring;
use App\Models\SpringFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class SpringFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $spring_code
     * @return Response
     */
    public function index(string $spring_code)
    {
        $spring = Spring::where('code', $spring_code)->with('feedback')->first();
        return Inertia::render('SpringFeedback/Index', ['spring' => $spring]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'feedback' => 'required',
        ])->validateWithBag('addFeedback');

        $request['user_id'] = Auth::id();
        $spring_feedback = SpringFeedback::create($request->all());

        // spring status back to submitted
        $spring_id = $request['spring_id'];
        $spring = Spring::where('id', $spring_id)->first();
        if ($spring->status === 'confirmed') {
            $spring->status = 'submitted';
            $spring->save();
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return \Illuminate\Http\Response
     */
    public function show(SpringFeedback $springFeedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return \Illuminate\Http\Response
     */
    public function edit(SpringFeedback $springFeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpringFeedback $springFeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpringFeedback $springFeedback)
    {
        //
    }

    public function view(int $spring_id, Request $request)
    {
        $length = $request->input('length');
        $orderBy = $request->input('column', 'created_at'); //Index
        $orderByDir = $request->input('dir', 'desc');
        $query = SpringFeedback::where('spring_id', $spring_id)->with('user')->orderBy($orderBy, $orderByDir);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }
}
