<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserRole;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::findOrFail(\Auth::user()->id);
        $userRoles = UserRole::all();
        return view('user-bundle.profile.edit', compact('users', 'userRoles'));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

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
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
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
        // $user = User::findOrFail(\Auth::user()->id);
        // $data = $request->all();
        // $this->validator($data)->validate();
        // $user->name = $data['name'];
        // $user->password =  Hash::make($data['password']);
        // // if (($data['password'] && $data['password_confirmation']) && ($data['password'] === $data['password_confirmation'])) {
        // //     $user->password =  Hash::make($data['password']);
        // // }
        // if ($user->save()) {
        //     $msg = "success update profile";
        // } else {
        //     $msg = "failed update profile";
        // }
        // return redirect()->route('profile.index')->with(['message' => $msg]);

        try {
            $user = User::findOrFail(\Auth::user()->id);
            $data = $request->all();
            $request->validate([
                'fullname' => 'required|string|unique:users,fullname,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
            ]);
            // $this->validator($data)->validate();
            $status = 'Profile updated!';
            $success = true;
            if (!empty($request->password) && !empty($request->password_confirmation)) {
                // $this->validator($data)->validate();
                $user->update([
                    'name' => $request->fullname,
                    'fullname' => $request->fullname,
                    'password' => Hash::make($request->password)
                ]);
                \Auth::logout();
                return redirect('/login')
                    ->with('status', 'You have been logged out')->with('message', 'Please login again');
            }
            if (empty($request->password) && empty($request->password_confirmation)) {
                $user->update([
                    'name' => $request->fullname,
                    'fullname' => $request->fullname,
                ]);
            }
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('profile-web.index')->with('status', $status)->with('success', $success);
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

    }
}
