<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\LivestreamLike;
use App\Http\Requests\StoreLivestreamLikeRequest;
use App\Http\Requests\UpdateLivestreamLikeRequest;

class LivestreamLikeController extends Controller
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
     * @param  \App\Http\Requests\StoreLivestreamLikeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $like = LivestreamLike::where('livestream_id', $request->livestream_id)->where('user_apps_id', auth()->user()->id)->first();

            if ($like) {
                $like = $like->delete();
            }else{
                $like = LivestreamLike::create([
                    'livestream_id' => $request->livestream_id
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
     * @param  \App\Models\LivestreamLike  $livestreamLike
     * @return \Illuminate\Http\Response
     */
    public function show(LivestreamLike $livestreamLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLivestreamLikeRequest  $request
     * @param  \App\Models\LivestreamLike  $livestreamLike
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLivestreamLikeRequest $request, LivestreamLike $livestreamLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LivestreamLike  $livestreamLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(LivestreamLike $livestreamLike)
    {
        //
    }
}
