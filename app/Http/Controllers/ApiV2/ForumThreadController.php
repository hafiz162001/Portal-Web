<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Forum;
use App\Models\ForumMember;
use App\Models\ForumThread;
use App\Http\Requests\StoreForumThreadRequest;
use App\Http\Requests\UpdateForumThreadRequest;
use App\Http\Resources\ThreadResource;

class ForumThreadController extends Controller
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
        $perPage = 500; 

        try {
            $threads = ForumThread::query()->withCount('likes','messages','views')->with('user', 'is_liked')->orderByDesc('id');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $threads = $threads->orderBy('id', $sort);
            }else {
                $threads = $threads->orderByDesc('id');
            }

            if ($request->q) {
                $safetyFields = ['title', 'forum_id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $threads = $threads->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->my_threads && $request->my_threads == 1)
            {
                $threads = $threads->Where([ ['user_apps_id', '=', auth()->user()->id] ]);
            }

            if ($request->thread_id)
            {
                $threads = $threads->Where([ ['id', '=', $request->thread_id] ]);
            }

            if ($request->perPage) {
                // $perPage = $request->perPage;
            }

            $threads = $threads->paginate($perPage)->appends($request->query());

            if (count($threads) < 1) {
                $msg = 'Threads Not Found';
            }

            // $data = $threads;
            $data = ThreadResource::collection($threads);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? '' : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? '' : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreForumThreadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $forum = Forum::where('id', $request->forum_id)->first();

            if ($forum) {
                if ($forum->type == 'private') {
                    $forumMember = ForumMember::where([ ['user_apps_id', '=', auth()->user()->id], ['forum_id', '=', $request->forum_id], ['status', '=', 1] ])->first();
                    if (!$forumMember) {
                        throw new \Exception("Kamu bukan anggota forum");
                    }
                }
            }

            $forumThread = ForumThread::create($request->except(['link_poto_video']));

            $data = ThreadResource::make($forumThread);

            if ($request->link_poto_video) {
                $file      = $request->link_poto_video;

                if (strpos($file, ";base64,")) {
                    $parts     = explode(";base64,", $file);
                    $fileparts = explode("image/", @$parts[0]);
                    $filetype  = $fileparts[1];
                    $fileName  = md5(microtime()). '.' . $filetype;
                    \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                    ForumThread::where('id', $data->id)
                    ->update(['link_poto_video' => $fileName]);

                }else {
                    ForumThread::where('id', $data->id)
                    ->update(['link_poto_video' => $file]);
                }
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
     * Display the specified resource.
     *
     * @param  \App\Models\ForumThread  $forumThread
     * @return \Illuminate\Http\Response
     */
    public function show(ForumThread $forumThread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateForumThreadRequest  $request
     * @param  \App\Models\ForumThread  $forumThread
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateForumThreadRequest $request, ForumThread $forumThread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ForumThread  $forumThread
     * @return \Illuminate\Http\Response
     */
    public function destroy(ForumThread $forumThread)
    {
        //
    }

    public function deleteThreads(Request $request){
        $success = true;
        $data = null;
        $msg = null;

        try {
            if (isset($request->id)) {
                $delete = ForumThread::where('id', $request->id)->delete();
                $data = $delete;
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }
}
