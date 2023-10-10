<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Forum;
use App\Models\ForumTopic;
use App\Http\Requests\StoreForumRequest;
use App\Http\Requests\UpdateForumRequest;
use App\Http\Resources\ForumResource;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 25;
        $topic = [];

        try {
            $topic = ForumTopic::get();
            // $forum = Forum::query()->withCount('threads')->get();

            foreach ($topic as $key => $val) {
                $forum = Forum::query()->where('topic_id', $val->id)->where('id', '!=', 3)->withCount('threads', 'isMember')->get();

                $dataForum = [];
                foreach ($forum as $keys => $valForum) {
                    $is_join = false;
                    if ($valForum->is_member_count > 0) {
                        $is_join = true;
                    }

                    $dataForum[] = [
                            'id'          => $valForum->id,
                            'is_join'     => $is_join,
                            'name'        => $valForum->name,
                            'type'        => $valForum->type,
                            'description' => $valForum->description,
                            'treads_count'=> $valForum->threads_count,
                            'image'       => $valForum->image ? asset('img/' . $valForum->image) : null,
                            'cover_image' => $valForum->cover_image ? asset('img/' . $valForum->cover_image) : null,
                            'created_at'  => date('Y m d', strtotime($valForum->created_at)),
                        ];
                }

                $data[] = [
                    'title' => $val['name'],
                    'data' => $dataForum,
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreForumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreForumRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function show(Forum $forum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateForumRequest  $request
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateForumRequest $request, Forum $forum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forum $forum)
    {
        //
    }
}
