<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ThreadMessage;
use App\Http\Requests\StoreThreadMessageRequest;
use App\Http\Requests\UpdateThreadMessageRequest;
use App\Http\Resources\ThreadMessageResource;

class ThreadMessageController extends Controller
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
            $threadsMessage = ThreadMessage::query()->with('user');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $threadsMessage = $threadsMessage->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['name', 'forum_id', 'forum_thread_id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $threadsMessage = $threadsMessage->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->my_threads && $request->my_threads == 1)
            {
                $threadsMessage = $threadsMessage->Where([ ['user_apps_id', '=', auth()->user()->id] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $threadsMessage = $threadsMessage->paginate($perPage)->appends($request->query());

            if (count($threadsMessage) < 1) {
                $msg = 'Thread Messages Not Found';
            }

            // $data = $threads;
            $data = ThreadMessageResource::collection($threadsMessage);

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
     * @param  \App\Http\Requests\StoreThreadMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {

            $message = ThreadMessage::create($request->except(['user_apps_id']));

            $data = ThreadMessageResource::make($message);
            ThreadMessage::where('id', $data->id)->update(
                ['user_apps_id' => auth()->user()->id]
            );

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
     * @param  \App\Models\ThreadMessage  $threadMessage
     * @return \Illuminate\Http\Response
     */
    public function show(ThreadMessage $threadMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateThreadMessageRequest  $request
     * @param  \App\Models\ThreadMessage  $threadMessage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateThreadMessageRequest $request, ThreadMessage $threadMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThreadMessage  $threadMessage
     * @return \Illuminate\Http\Response
     */
    public function deleteMessages(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        DB::beginTransaction();

        try {
            $threadMessage = ThreadMessage::where('id', $request->id)->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

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
}
