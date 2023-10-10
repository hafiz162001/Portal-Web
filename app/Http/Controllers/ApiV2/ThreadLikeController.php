<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ThreadLike;
use App\Http\Requests\StoreThreadLikeRequest;
use App\Http\Requests\UpdateThreadLikeRequest;

class ThreadLikeController extends Controller
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
     * @param  \App\Http\Requests\StoreThreadLikeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $like = ThreadLike::where('forum_thread_id', $request->forum_thread_id)->where('user_apps_id', auth()->user()->id)->first();

            if ($like) {
                $like = $like->delete();
            }else{
                $like = ThreadLike::create([
                    'forum_thread_id' => $request->forum_thread_id,
                    'user_apps_id' => auth()->user()->id
                ]);
            }

            $data = $like;

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
     * @param  \App\Models\ThreadLike  $threadLike
     * @return \Illuminate\Http\Response
     */
    public function show(ThreadLike $threadLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateThreadLikeRequest  $request
     * @param  \App\Models\ThreadLike  $threadLike
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateThreadLikeRequest $request, ThreadLike $threadLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThreadLike  $threadLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThreadLike $threadLike)
    {
        //
    }
}
