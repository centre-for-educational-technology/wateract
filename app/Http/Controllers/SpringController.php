<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\County;
use App\Models\Photo;
use App\Models\Spring;
use App\Models\SpringDatabaseLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
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
        $user = Auth::user();
        if ($user && $user->hasRole(['editor', 'admin', 'super-admin'])) {
            $springs = Spring::whereIn('status', ['submitted', 'confirmed'])->get();
            $featured_springs = Spring::where('featured', '1')->with('all_photos')->with('country_info')->inRandomOrder()->limit(4)->get();
            $newest_springs = Spring::whereIn('status', ['submitted', 'confirmed'])->with('all_photos')->with('country_info')->orderBy('created_at', 'desc')->limit(4)->get();
        } else {
            $springs = Spring::whereIn('status', ['submitted', 'confirmed'])->where('unlisted', 0)->get();
            $featured_springs = Spring::where('featured', '1')->where('unlisted', 0)->with('all_photos')->with('country_info')->inRandomOrder()->limit(4)->get();
            $newest_springs = Spring::whereIn('status', ['submitted', 'confirmed'])->where('unlisted', 0)->with('all_photos')->with('country_info')->orderBy('created_at', 'desc')->limit(4)->get();
        }
        return Inertia::render('Springs/Index', [
            'springs' => $springs,
            'featured_springs' => $featured_springs,
            'newest_springs' => $newest_springs,
            'classifications' => SpringController::getClassifications(),
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
                'countries' => Country::all(),
                'classifications' => SpringController::getClassifications(),
                'ownerships' => SpringController::getOwnerships(),
                'statuses' => SpringController::getStatuses()
            ]);
        }
        return SpringController::index();
    }

    public function getClassifications() {
        return [
            array( 'id' => 'gravity_spring', 'name' => 'springs.classification_options.gravity_spring' ),
            array( 'id' => 'artesian_spring', 'name' => 'springs.classification_options.artesian_spring' ),
        ];
    }

    public function getOwnerships() {
        return [
            array('id' => 'private_property', 'name' => 'springs.ownership_options.private_property'),
            array('id' => 'state_property', 'name' => 'springs.ownership_options.state_property'),
            array('id' => 'municipal_property', 'name' => 'springs.ownership_options.municipal_property'),
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

        $country_code = $request['country'];

        Validator::make($request->all(), [
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'country' => 'required',
            "references.*.url" => "nullable|url",
        ])->after(function ($validator) use ($country_code)  {
        $allowed_countries = Country::all()->pluck('code')->all();
        if (! in_array($country_code, $allowed_countries)) {
            $validator->errors()->add(
                'country', __('springs.country_not_supported')
            );
        }
    })->validateWithBag('addSpring');


        $request['user_id'] = Auth::id();
        // create wateract code
        $new_id = DB::table('springs')->max('id') + 1;
        $code = sprintf('%s%05d', $request['country'], $new_id);

        $request['code'] = $code;

        $country_id = SpringController::getCountryId($request['country']);
        if ($country_id) {
            $request['country_id'] = $country_id;
        }
        $county_id = SpringController::getCountyId($request['county']);
        if ($county_id) {
            $request['county_id'] = $county_id;
        }

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

        return redirect()->route('springs.index')
            ->with('success','Spring created successfully.');
    }

    public function getCountryId($country_code) {
        $country_id = null;
        $country_obj = Country::where('code', $country_code)->first();
        if ($country_obj) {
            $country_id = $country_obj->id;
        }
        return $country_id;
    }

    public function getCountyId($county_name) {
        $county_id = null;
        $county_obj = County::where('name', $county_name)->first();
        if ($county_obj) {
            $county_id = $county_obj->id;
        }
        return $county_id;
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
            ->with('user')
            ->with('references')
            ->with('database_links')
            ->with('all_photos')
            ->first();

        $photo_url = '';
        if ( count($spring->all_photos) > 0 ) {
            $base_url = env('APP_URL', '');
            $photo_url = $base_url . '/' . $spring->all_photos[0]->thumbnail;
        }

        return Inertia::render('Springs/Show', ['spring' => $spring])
            ->withViewData(['og_title' => $spring->name, 'og_image' => $photo_url]);
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
            ->with('user')
            ->with('references')
            ->with('database_links')
            ->with('photos')
            ->first();
        if (Auth::user()) {
            return Inertia::render('Springs/Edit', [
                'countries' => Country::all(),
                'classifications' => SpringController::getClassifications(),
                'ownerships' => SpringController::getOwnerships(),
                'spring' => $spring]);
        }
        return SpringController::show($spring);
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

        $country_code = $request['country'];

        Validator::make($request->all(), [
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'country' => 'required',
            "references.*.url"  => "nullable|url",
        ])->after(function ($validator) use ($country_code)  {
            $allowed_countries = Country::all()->pluck('code')->all();
            if (! in_array($country_code, $allowed_countries)) {
                $validator->errors()->add(
                    'country', __('springs.country_not_supported')
                );
            }
        })->validateWithBag('editSpring');

        $country_id = SpringController::getCountryId($request['country']);
        if ($country_id) {
            $request['country_id'] = $country_id;
        }
        $county_id = SpringController::getCountyId($request['county']);
        if ($county_id) {
            $request['county_id'] = $county_id;
        }

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
                    Storage::disk('public')->delete($photo->thumbnail);
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        if ( !$request->has('spring_id') ) {
            return redirect()->back();
        }

        $spring_id = $request->input('spring_id');
        $spring = Spring::where('id', $spring_id)->first();

        $this->authorize('delete', $spring);

        // delete spring photos
        $photos = Photo::where('spring_id', $spring_id)->get();
        foreach($photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            Storage::disk('public')->delete($photo->thumbnail);
            $photo->delete();
        }

        // deletes spring, and also related database relations (including observations and measurements)
        $spring->delete();

        return Redirect::route('springs.index')
            ->with('success', 'Spring deleted successfully');
    }

    public function getSprings(Request $request)
    {
        $user = Auth::user();

        $spring_id = $request->input('spring_id');
        // for edit map get all springs except given spring
        if ($spring_id) {
            if ($user && $user->hasRole(['editor', 'admin', 'super-admin'])) {
                $springs = Spring::where('id', '!=', $spring_id)
                    ->whereIn('status', ['submitted', 'confirmed'])
                    ->get();
            } else {
                $springs = Spring::where('unlisted', 0)
                    ->where('id', '!=', $spring_id)
                    ->whereIn('status', ['submitted', 'confirmed'])
                    ->get();
            }
            return response()->json($springs);
        }

        $name = $request->input('name');
        $classification = $request->input('classification');
        if ($user && $user->hasRole(['editor', 'admin', 'super-admin'])) {
            if ($classification) {
                $springs = Spring::where('name', 'LIKE', '%' . $name . '%')
                    ->where('classification', $classification)
                    ->whereIn('status', ['submitted', 'confirmed'])
                    ->get();
            } else {
                $springs = Spring::where('name', 'LIKE', '%' . $name . '%')
                    ->whereIn('status', ['submitted', 'confirmed'])
                    ->get();
            }
        } else {
            if ($classification) {
                $springs = Spring::where('name', 'LIKE', '%' . $name . '%')
                    ->where('classification', $classification)
                    ->where('unlisted', 0)
                    ->whereIn('status', ['submitted', 'confirmed'])
                    ->get();
            } else {
                $springs = Spring::where('name', 'LIKE', '%' . $name . '%')
                    ->where('unlisted', 0)
                    ->whereIn('status', ['submitted', 'confirmed'])
                    ->get();
            }
        }

        return response()->json($springs);
    }

    public function springsForReview(Request $request)
    {
        $user = User::where('id', Auth::id())->first();
        $length = $request->input('length');
        $orderBy = $request->input('column', 'created_at');
        $orderByDir = $request->input('dir', 'desc');
        if ($user->hasRole('editor')) {
            $user_counties_ids = $user->user_counties_ids();
            $query = Spring::where('status', 'submitted')
                ->whereIn('county_id', $user_counties_ids)
                ->orderBy($orderBy, $orderByDir);
            $data = $query->paginate($length);
            return new DataTableCollectionResource($data);
        }
        $query = Spring::where('status', 'submitted')->with('feedback')->orderBy($orderBy, $orderByDir);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

}
