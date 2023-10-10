<?php

namespace App\Http\Controllers;

use App\Models\LivestreamViews;
use App\Http\Requests\StoreLivestreamViewsRequest;
use App\Http\Requests\UpdateLivestreamViewsRequest;

class LivestreamViewsController extends Controller
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
     * @param  \App\Http\Requests\StoreLivestreamViewsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLivestreamViewsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LivestreamViews  $livestreamViews
     * @return \Illuminate\Http\Response
     */
    public function show(LivestreamViews $livestreamViews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLivestreamViewsRequest  $request
     * @param  \App\Models\LivestreamViews  $livestreamViews
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLivestreamViewsRequest $request, LivestreamViews $livestreamViews)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LivestreamViews  $livestreamViews
     * @return \Illuminate\Http\Response
     */
    public function destroy(LivestreamViews $livestreamViews)
    {
        //
    }
}
