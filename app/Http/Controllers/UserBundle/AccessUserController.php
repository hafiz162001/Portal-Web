<?php

namespace App\Http\Controllers\UserBundle;

use App\Models\User;
use App\Models\SubMenu;
use App\Models\Location;
use App\Models\UserRole;
use App\Models\BlocLocation;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccessUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('access-users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role_code == 'ADMIN') {
            $userRoles = UserRole::whereNotIn('code', ['SUPERUSER'])->get();
        } else {
            $userRoles = UserRole::all();
        }
        
        $locations = Location::orderby('name', 'ASC')->get();
        $blocLocations = BlocLocation::all();
        $userCategories = User::getUserCategories();
        return view('access-users.create', compact('userRoles', 'locations', 'blocLocations', 'userCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->toArray());
        $data = $request->all();
        $message = "User add Failed";
        if (($data['password'] && $data['password_confirmation']) && ($data['password'] === $data['password_confirmation'])) {
            $this->validator($data)->validate();
            $users = User::create([
                'name' => $data['fullname'],
                'email' => $data['email'],
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role_code' => $data['role_code'],
            ]);
            if ($users->id) {
                $message = 'User added successfully';
                return redirect()->route('access-users.edit', $users->id)->with(['message' => $message]);
            }
        }
        return redirect()->route('access-users.index')->with(['message' => $message]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $users = User::findOrFail($id);
        $menus = \App\Models\Menu::all();
        // $menuCashier = \App\Models\Menu::where('id', 3)->orWhere('id', 5)->get();
        $userRoles = UserRole::all();
        $locations = Location::orderby('name', 'ASC')->get();
        $submenus = SubMenu::all();
        $blocLocations = BlocLocation::all();
        $userCategories = User::getUserCategories();
        return  view('access-users.edit', compact('users', 'menus', 'userRoles', 'locations', 'submenus', 'blocLocations', 'userCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'fullname' => 'required|string|unique:users,fullname,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_code' => 'required',
        ]);
        $data = $request->all();
        $msg = "failed update user";

        $user->username = $data['username'];
        $user->name = $data['fullname'];
        $user->email = $data['email'];
        $user->fullname = $data['fullname'];
        $user->role_code = $data['role_code'];
        $user->active = $data['active'];
        if (($data['password'] && $data['password_confirmation']) && ($data['password'] === $data['password_confirmation'])) {
            $user->password =  Hash::make($data['password']);
            session()->flush();
        }

        if ($user->save()) {
            $msg = "success update user";
        }
        return redirect()->route('access-users.edit', $id)->with(['message' => $msg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $users = User::findOrFail($id);
        $users->delete();
        return redirect()->route('access-users.index')->with(['message' => 'User deleted successfully']);
    }

    public function getLocationAccessUser(Request $request)
    {
        $id = $request->id;
        $location = Location::where('bloc_location_id', $id)->orderby('name', 'ASC')->get();

        $msg = [
            'location' => $location
        ];

        echo json_encode($msg);
    }
}
