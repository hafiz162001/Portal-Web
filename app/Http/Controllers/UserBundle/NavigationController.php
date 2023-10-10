<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Navigation;
use App\DataTables\NavigationDataTable;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NavigationDataTable $dataTable)
    {
        return $dataTable->render('navigation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('navigation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $navi = Navigation::where('code', $request->code)->count();
        if ($navi > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
        try {
            $status = 'Navigation stored!';
            $success = true;
            Navigation::create($request->all());
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }

        return redirect()->route('navi.index')->with('status', $status)->with('success', $success);
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
        $navi = Navigation::find($id);
        return view('navigation.edit', compact('navi'));
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
        $navi = Navigation::find($id);
        $navis = Navigation::where('code', $request->code)->where('id', '!=', $navi->id)->count();
        if ($navis > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
        try {
            $status = 'Navigation updated!';
            $success = true;
            $navi->update($request->all());
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('navi.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $navi = Navigation::find($id);
        try {
            $status = "Navigation can't be deleted because it has a relationships!";
            $success = false;
            if ($navi->delete()) {
                $status = "Navigation deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('navi.index')->with('status', $status)->with('success', $success);
    }
}
