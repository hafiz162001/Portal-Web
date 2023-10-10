<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests\StoreForumTopicRequest;
use App\Http\Requests\UpdateForumTopicRequest;
use App\Http\Resources\ForumTopicResource;
use App\Models\ForumTopic;

class ForumTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreForumTopicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreForumTopicRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ForumTopic  $forumTopic
     * @return \Illuminate\Http\Response
     */
    public function show(ForumTopic $forumTopic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateForumTopicRequest  $request
     * @param  \App\Models\ForumTopic  $forumTopic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateForumTopicRequest $request, ForumTopic $forumTopic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ForumTopic  $forumTopic
     * @return \Illuminate\Http\Response
     */
    public function destroy(ForumTopic $forumTopic)
    {
        //
    }
}
