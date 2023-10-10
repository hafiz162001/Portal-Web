<?php

namespace App\Http\Controllers;

use App\DataTables\TermConditionDataTable;
use App\Http\Requests\TermConditionRequest;
use App\Models\SyaratKetentuan;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TermConditionDataTable $dataTable)
    {
        return $dataTable->render('term-condition.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('term-condition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TermConditionRequest $request)
    {
        try {
            $request->validated();
            $status = 'Term & condition stored!';
            $success = true;
            SyaratKetentuan::create($request->all());
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('term-condition.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $termCondition = SyaratKetentuan::findOrFail($id);
        return view('term-condition.show', compact('termCondition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SyaratKetentuan $termCondition)
    {
        return view('term-condition.edit', compact('termCondition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TermConditionRequest $request, SyaratKetentuan $termCondition)
    {
        try {
            $request->validated();
            $status = 'Term & condition updated!';
            $success = true;
            // $termCondition->update($request->all());
            $termCondition->update($request->except('category'));
            $termCondition->category = 'blocx';
            $termCondition->update();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('term-condition.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SyaratKetentuan $termCondition)
    {
        try {
            if ($termCondition->delete()) {
                $status = "Term & condition deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('term-condition.index')->with('status', $status)->with('success', $success);
    }
}
