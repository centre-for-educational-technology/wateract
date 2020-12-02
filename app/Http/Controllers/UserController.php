<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\County;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->with('roles')->get();
        return Inertia::render('Users/Index', ['users' => $users]);
        /*$users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 5);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Inertia\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        //$user = User::find($id);
        //$roles = Role::where('name', '!=', 'super-admin')->pluck('name','name')->all();
        $roles = Role::where('name', '!=', 'super-admin')->get();
        $counties = County::all();

        $all_counties = [];
        $countries = Country::all();
        foreach($countries as $country) {
            $country_data = [];
            $country_data['country'] = $country->name;
            $country_data['counties'] = County::where('country_id', $country->id)->get();
            $all_counties []= $country_data;
        }

        /*$user_role = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','user_role'));*/

        //$spring = Spring::where('id', $spring->id)->with('references')->with('database_links')->first();
        //if (Auth::user()->) { // check if is admin

        // get user role, each user has only one role
        $user_role = "";
        $user_role_names = $user->getRoleNames();
        if (count($user_role_names) > 0) {
            $user_role = $user_role_names[0];
        }

        // get user counties
        $user_counties_ids = [];
        $user_counties = DB::table('model_has_counties')
            ->where('model_id', $user->id)
            ->where('model_type', 'App\Models\User')
            ->get();
        foreach ($user_counties as $user_county) {
            $county_obj = County::where('id', $user_county->county_id)->first();

            $user_counties_id_data = [];
            $user_counties_id_data ['id']= $user_county->county_id;
            $user_counties_id_data ['name'] = $county_obj->name;
            $user_counties_ids []= $user_counties_id_data;
        }

        return Inertia::render('Users/Edit', [
                'roles' => $roles,
                'all_counties' => $all_counties,
                'user_role' => $user_role,
                'user_counties' => $user_counties_ids,
                'user' => $user ]);
        //}
        //return Inertia::render('Springs/Show', ['spring' => $spring]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        //Gate::authorize('update', $post);

        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ])->validateWithBag('updateUser');

        //update user
        $user->update($request->all());

        // update user roles
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->assignRole($request->input('role'));

        // update user counties
        DB::table('model_has_counties')
            ->where('model_id',$user->id)
            ->where('model_type', 'App\Models\User')
            ->delete();
        $counties = $request->input('counties');
        if (!empty($counties)) {
            foreach ($counties as $county) {
                DB::table('model_has_counties')->insert(
                    ['county_id' => $county['id'], 'model_type' => 'App\Models\User', 'model_id' => $user->id]
                );
            }
        }

        return redirect()->route('users.index')
            ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');
    }*/
    public function destroy(Request $request)
    {
        /*if ($request->has('id')) {
            User::find($request->input('id'))->delete();
            return redirect()->back();
        }*/
    }
}