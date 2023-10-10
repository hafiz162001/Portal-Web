<?php

namespace App\Http\Controllers\ApiV2;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\File;

use App\Models\ForumMember;
use App\Http\Requests\StoreForumMemberRequest;
use App\Http\Requests\UpdateForumMemberRequest;

class ForumMemberController extends Controller
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

        try {
            $forum = Contestan::query()->with('topic')->orderBy('id', 'desc');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $forum = $forum->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $forum = $forum->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1)
            {
                $forum = $forum->Where([ ['highlight', '=', 1] ]);
            }

            if ($request->my_profile && $request->my_profile == 1)
            {
                $forum = $forum->Where([ ['user_apps_id', '=', auth()->user()->id] ]);
            }

            if ($request->other && $request->other == 1 && !empty($request->id) && !empty($request->category_id))
            {
                $forum = $forum->Where([ ['id', '<>', $request->id],['category_id', '=', $request->category_id] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $forum = $forum->paginate($perPage)->appends($request->query());

            if (count($forum) < 1) {
                $msg = 'Contestan Not Found';
            }

            // $data = $forum;
            $data = ContestanResource::collection($forum);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreForumMemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            // DB::beginTransaction();
            $forum_member = New ForumMember();
            $forum_member->forum_id = $request->forum_id;
            $forum_member->user_apps_id = auth()->user()->id;
            $forum_member->status = 0;
            $forum_member->save();

            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
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
     * Display the specified resource.
     *
     * @param  \App\Models\ForumMember  $forumMember
     * @return \Illuminate\Http\Response
     */
    public function show(ForumMember $forumMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateForumMemberRequest  $request
     * @param  \App\Models\ForumMember  $forumMember
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateForumMemberRequest $request, ForumMember $forumMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ForumMember  $forumMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(ForumMember $forumMember)
    {
        //
    }
}
