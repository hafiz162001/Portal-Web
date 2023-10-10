<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ThreadView;
use App\Http\Requests\StoreThreadViewRequest;
use App\Http\Requests\UpdateThreadViewRequest;

class ThreadViewController extends Controller
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
     * @param  \App\Http\Requests\StoreThreadViewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $threadView = ThreadView::create(['forum_thread_id' => $request->forum_thread_id]);
            $data = $threadView;
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
     * @param  \App\Models\ThreadView  $threadView
     * @return \Illuminate\Http\Response
     */
    public function show(ThreadView $threadView)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateThreadViewRequest  $request
     * @param  \App\Models\ThreadView  $threadView
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateThreadViewRequest $request, ThreadView $threadView)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThreadView  $threadView
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThreadView $threadView)
    {
        //
    }
}
