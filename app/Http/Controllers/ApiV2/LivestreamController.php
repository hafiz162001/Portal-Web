<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Livestream;
use App\Http\Requests\StoreLivestreamRequest;
use App\Http\Requests\UpdateLivestreamRequest;
use App\Http\Resources\LivestreamResource;

class LivestreamController extends Controller
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
        $perPage = 6;

        try {

            $livestream = Livestream::query()->where('status', 1)->with('user');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $livestream = $livestream->orderBy('id', $sort);
            }else {
                $livestream = $livestream->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $livestream = $livestream->where($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $livestream = $livestream->paginate($perPage)->appends($request->query());

            if (count($livestream) < 1) {
                $msg = 'Livestream Not Found';
            }

            // $data = $livestream;
            $data = LivestreamResource::collection($livestream);

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
     * @param  \App\Http\Requests\StoreLivestreamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $livestream = Livestream::create([
                'name' => $request->name,
                'link'=> $request->links,
                'user_apps_id' => auth()->user()->id,
            ]);

            $data = LivestreamResource::make($livestream);

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
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
     * @param  \App\Models\Livestream  $livestream
     * @return \Illuminate\Http\Response
     */
    public function show(Livestream $livestream)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLivestreamRequest  $request
     * @param  \App\Models\Livestream  $livestream
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLivestreamRequest $request, Livestream $livestream)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Livestream  $livestream
     * @return \Illuminate\Http\Response
     */
    public function destroy(Livestream $livestream)
    {
        //
    }

    public function stopLivestream(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $livestream = Livestream::where('id', $request->id)->update([
                'status' => 0
            ]);

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $livestream,
            'message' => $msg,
        ];

        return $resp;
    }
}
