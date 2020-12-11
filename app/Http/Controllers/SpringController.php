<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Spring;
use App\Models\SpringDatabaseLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use function Psy\debug;
use Illuminate\Support\Facades\Auth;
use App\Models\SpringReference;

class SpringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $springs = Spring::whereIn('status', ['submitted', 'confirmed'])->get();
        $featured_springs = Spring::where('featured', '1')->with('photos')->inRandomOrder()->limit(4)->get();
        $newest_springs = Spring::whereIn('status', ['submitted', 'confirmed'])->with('photos')->orderBy('created_at', 'desc')->limit(4)->get();
        return Inertia::render('Springs/Index', [
            'springs' => $springs,
            'featured_springs' => $featured_springs,
            'newest_springs' => $newest_springs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (Auth::user()) {
            return Inertia::render('Springs/Create', [
                'classifications' => SpringController::getClassifications(),
                'ownerships' => SpringController::getOwnerships(),
                'statuses' => SpringController::getStatuses()
            ]);
        }
        $springs = Spring::whereIn('status', ['submitted', 'confirmed'])->get();
        return Inertia::render('Springs/Index', ['springs' => $springs]);
    }

    public function getClassifications() {
        return [
            array( 'id' => 'rheocrene', 'name' => 'Rheocrene'),
            array('id' => 'hillslope_spring', 'name' => 'Hillslope spring'),
            array('id' => 'limnocrene', 'name' => 'Limnocrene'),
            array('id' => 'helocrene', 'name' => 'Helocrene'),
            array('id' => 'cave_spring', 'name' => 'Cave spring'),
            array('id' => 'hypocrene', 'name' => 'Hypocrene'),
            array('id' => 'captured_spring', 'name' => 'Captured spring'),
            array('id' => 'karst_spring', 'name' => 'Karst spring'),
        ];
    }

    public function getOwnerships() {
        return [
            array('id' => 'private_property', 'name' => 'Private property'),
            array('id' => 'state_property', 'name' => 'State property'),
            array('id' => 'municipal_property', 'name' => 'Municipal property'),
        ];
    }

    public function getStatuses() {
        return [
            array('id' => 'draft', 'name' => 'Draft'),
            array('id' => 'submitted', 'name' => 'Submitted'),
            array('id' => 'confirmed', 'name' => 'Confirmed'),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            "references.*.url"  => "nullable|url",
        ])->validateWithBag('addSpring');

        $request['user_id'] = Auth::id();
        // create wateract code
        $new_id = DB::table('springs')->max('id') + 1;
        $code = sprintf('%s%05d', $request['country'], $new_id);

        $request['code'] = $code;
        $spring = Spring::create($request->all());

        SpringController::saveReferences($spring, $request['references']);
        SpringController::saveDatabaseLinks($spring, $request['database_links']);

        if (!empty($request['photo_ids'])) {
            foreach($request['photo_ids'] as $photo_id) {
                $photo = Photo::where('id', $photo_id)->first();
                if ($photo) {
                    $photo->spring_id = $spring->id;
                    $photo->save();
                }
            }
        }

        /*if (!empty($request['photos'])) {
            foreach ($request['photos'] as $photo_raw) {
                var_dump($photo_raw);
                $photo = new Photo();
                $photo->spring_id = $spring->id;
                $path = Storage::disk('public')->put('spring-photos', $photo_raw);
                $photo->path = $path;
                $photo->save();
            }
        }*/

        return redirect()->route('springs.index')
            ->with('success','Spring created successfully.');
    }

    public function saveReferences($spring, $references_info) {
        if (!empty($references_info)) {
            foreach ($references_info as $reference_info) {
                $spring_reference = new SpringReference();
                if (isset($reference_info['url'])) {
                    $spring_reference->spring_id = $spring['id'];
                    $spring_reference->url = $reference_info['url'];
                    if (isset($reference_info['url_title'])) {
                        $spring_reference->url_title = $reference_info['url_title'];
                    }
                    $spring_reference->save();
                }
            }
        }
    }

    public function saveDatabaseLinks($spring, $databases_info) {
        if (isset($databases_info)) {
            //$databases = json_decode($databases_info);
            foreach ($databases_info as $database_info) {
                if (isset($database_info['id'])) {
                    $spring_database_link = SpringDatabaseLink::find($database_info['id']);
                    if (empty($database_info['database_name']) && empty($database_info['code'])
                        && empty($database_info['spring_name']) && empty($database_info['url'])) {
                        $spring_database_link->delete();
                    }
                    $spring_database_link->database_name = $database_info['database_name'];
                    $spring_database_link->code = $database_info['code'];
                    $spring_database_link->spring_name = $database_info['spring_name'];
                    $spring_database_link->url = $database_info['url'];
                    $spring_database_link->save();
                } else {
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
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Spring $spring
     * @return Response
     */
    public function show(Spring $spring)
    {
        $spring = Spring::where('id', $spring->id)
            ->with('references')
            ->with('database_links')
            ->with('photos')
            ->first();
        return Inertia::render('Springs/Show', ['spring' => $spring]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Spring $spring
     * @return Response
     */
    public function edit(Spring $spring)
    {
        $spring = Spring::where('id', $spring->id)
            ->with('references')
            ->with('database_links')
            ->with('photos')
            ->first();
        if (Auth::user()) {
            return Inertia::render('Springs/Edit', [
                'classifications' => SpringController::getClassifications(),
                'ownerships' => SpringController::getOwnerships(),
                'spring' => $spring]);
        }
        return Inertia::render('Springs/Show', ['spring' => $spring]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Spring $spring
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Spring $spring)
    {
        $this->authorize('update', $spring);

        Validator::make($request->all(), [
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            "references.*.url"  => "nullable|url",
        ])->validateWithBag('editSpring');

        $spring->update($request->all());

        foreach ($request['references'] as $reference_info) {
            if (isset($reference_info['id'])) {
                //$spring_reference = SpringReference::where('id' , '=' , $reference_info['id'] )->get();
                $spring_reference = SpringReference::find($reference_info['id']);
                if (isset($reference_info['url'])) {
                    $spring_reference->url = $reference_info['url'];
                    if (isset($reference_info['url_title'])) {
                        $spring_reference->url_title = $reference_info['url_title'];
                    }
                    $spring_reference->save();
                }
            } else {
                //var_dump($reference_info);exit;
                $spring_reference = new SpringReference();
                if (isset($reference_info['url'])) {
                    $spring_reference->spring_id = $spring->id;
                    $spring_reference->url = $reference_info['url'];
                    if (isset($reference_info['url_title'])) {
                        $spring_reference->url_title = $reference_info['url_title'];
                    }
                    $spring_reference->save();
                }
            }

        }

        SpringController::saveDatabaseLinks($spring, $request['database_links']);

        if (!empty($request['photos_to_add'])) {
            foreach($request['photos_to_add'] as $photo_id) {
                $photo = Photo::where('id', $photo_id)->first();
                if ($photo) {
                    $photo->spring_id = $spring->id;
                    $photo->save();
                }
            }
        }

        if (!empty($request['photos_to_delete'])) {
            foreach($request['photos_to_delete'] as $photo_id) {
                $photo = Photo::where('id', $photo_id)->first();
                if ($photo) {
                    Storage::disk('public')->delete($photo->path);
                    $photo->delete();
                }
            }
        }


        return redirect()->route('springs.show', compact('spring'))
            ->with('success','Spring updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Spring $spring
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Spring $spring)
    {
        $this->authorize('delete', $spring);

        $spring->delete();

        return redirect()->route('springs.index')
            ->with('success', 'Spring deleted successfully');
    }

}
