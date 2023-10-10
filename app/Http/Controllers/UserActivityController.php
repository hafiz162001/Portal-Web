<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\DataTables\UserActivityDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Datatables;

class UserActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request, Builder $dataTable)
    // {
    //     if ($request->ajax()) {
    //         $useractivities = UserActivity::all();
    //         return Datatables::of($useractivities)->make(true);
    //     }
    //     $html = $dataTable
    //         ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
    //         ->addColumn(['data' => 'user.name', 'name' => 'user.name', 'title' => 'User Name'])
    //         ->addColumn(['data' => 'bloc_location.name', 'name' => 'bloc_location.name', 'title' => 'Location'])
    //         ->addColumn(['data' => 'checkin_at', 'name' => 'checkin_at', 'title' => 'Checkin At'])
    //         ->addColumn(['data' => 'checkout_at', 'name' => 'checkout_at', 'title' => 'Checkout At']);
    //     return view('user-activity.index')->with(compact('dataTable'));
    // }

    public function index(UserActivityDataTable $dataTable)
    {
        return $dataTable->render('user-activity.index');
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
     * Display the specified resource.
     *
     * @param  \App\Models\UserActivity  $userActivity
     * @return \Illuminate\Http\Response
     */
    public function show(UserActivity $userActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserActivity  $userActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(UserActivity $userActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserActivity  $userActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserActivity $userActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserActivity  $userActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserActivity $userActivity)
    {
        //
    }
}
