<?php

namespace App\Http\Controllers\UserBundle;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\UserRoleDataTable;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserRoleDataTable $dataTable)
    {
        return $dataTable->render('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = UserRole::where('code', $request->code)->count();
        if ($role > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
        try {
            $status = 'Role stored!';
            $success = true;
            $userRole = UserRole::create($request->all());
            $userRole->code = strtoupper($request->code);
            $userRole->save();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }

        return redirect()->route('role.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = UserRole::find($id);
        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = UserRole::find($id);
        $roles = UserRole::where('code', $request->code)->where('id', '!=', $role->id)->count();
        if ($roles > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
        try {
            $status = 'Role updated!';
            $success = true;
            $role->update([
                'code' => strtoupper($request->code),
                'view' => $request->view ?? false,
                'create' => $request->create ?? false,
                'update' => $request->update ?? false,
                'delete' => $request->delete ?? false,
            ]);
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('role.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = UserRole::find($id);
        $user = User::where('role_code', $role->code)->exists();
        try {
            $status = "Role can't be deleted because it has a relationships!";
            $success = false;
            if (!$user) {
                if ($role->delete()) {
                    $status = "Role deleted!";
                    $success = true;
                }
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('role.index')->with('status', $status)->with('success', $success);
    }
}
