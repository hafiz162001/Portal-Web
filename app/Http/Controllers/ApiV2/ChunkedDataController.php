<?php

namespace App\Http\Controllers;

use App\Models\ChunkedData;
use App\Http\Requests\StoreChunkedDataRequest;
use App\Http\Requests\UpdateChunkedDataRequest;

class ChunkedDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreChunkedDataRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChunkedDataRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChunkedData  $chunkedData
     * @return \Illuminate\Http\Response
     */
    public function show(ChunkedData $chunkedData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChunkedDataRequest  $request
     * @param  \App\Models\ChunkedData  $chunkedData
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChunkedDataRequest $request, ChunkedData $chunkedData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChunkedData  $chunkedData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChunkedData $chunkedData)
    {
        //
    }
}
