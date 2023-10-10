<?php

namespace App\Http\Controllers;

use App\DataTables\EvoriaTypeDataTable;
use Illuminate\Http\Request;
use App\DataTables\TypeDatatable;
use App\DataTables\TypeEvoriaDataTable;
use App\Http\Requests\TypeRequest;
use App\Models\Type;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EvoriaTypeDataTable $dataTable)
    {
        return $dataTable->render('evoria-bundle.type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('evoria-bundle.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request)
    {
        try {
            $request->validated();
            $status = 'Type stored!';
            $success = true;
            Type::create($request->all());
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('type.index')->with('status', $status)->with('success', $success);
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
    public function edit(Type $type)
    {
        return view('evoria-bundle.type.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TypeRequest $request, Type $type)
    {
        try {
            $request->validated();
            $status = 'Type updated!';
            $success = true;
            $type->update($request->all());
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('type.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        try {
            if ($type->delete()) {
                $status = "Type deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('type.index')->with('status', $status)->with('success', $success);
    }
}
