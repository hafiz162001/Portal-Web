<?php

namespace App\Http\Controllers;

use App\Models\LivestreamComment;
use App\Http\Requests\StoreLivestreamCommentRequest;
use App\Http\Requests\UpdateLivestreamCommentRequest;

class LivestreamCommentController extends Controller
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
     * @param  \App\Http\Requests\StoreLivestreamCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLivestreamCommentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LivestreamComment  $livestreamComment
     * @return \Illuminate\Http\Response
     */
    public function show(LivestreamComment $livestreamComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLivestreamCommentRequest  $request
     * @param  \App\Models\LivestreamComment  $livestreamComment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLivestreamCommentRequest $request, LivestreamComment $livestreamComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LivestreamComment  $livestreamComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(LivestreamComment $livestreamComment)
    {
        //
    }
}
