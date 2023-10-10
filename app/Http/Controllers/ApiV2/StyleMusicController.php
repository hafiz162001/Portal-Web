<?php

namespace App\Http\Controllers\ApiV2;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\MentorInterview;
use App\Models\StyleMusic;
use App\Http\Requests\StoreStyleMusicRequest;
use App\Http\Requests\UpdateStyleMusicRequest;
use App\Http\Resources\StyleMusicResource;

class StyleMusicController extends Controller
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
        $perPage = 50;

        try {
            $styleMusic = StyleMusic::query();

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $styleMusic = $styleMusic->orderBy('id', $sort);
                $styleMusic = $styleMusic->orderBy('hightlight', $sort);
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $styleMusic = $styleMusic->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1)
            {
                $styleMusic = $styleMusic->Where([ ['highlight', '=', 1] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $styleMusic = $styleMusic->paginate($perPage)->appends($request->query());

            if (count($styleMusic) < 1) {
                $msg = 'Styles Not Found';
            }

            // $data = $styleMusic;
            $data = StyleMusicResource::collection($styleMusic);
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
     * @param  \App\Http\Requests\StoreStyleMusicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStyleMusicRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StyleMusic  $styleMusic
     * @return \Illuminate\Http\Response
     */
    public function show(StyleMusic $styleMusic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStyleMusicRequest  $request
     * @param  \App\Models\StyleMusic  $styleMusic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStyleMusicRequest $request, StyleMusic $styleMusic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StyleMusic  $styleMusic
     * @return \Illuminate\Http\Response
     */
    public function destroy(StyleMusic $styleMusic)
    {
        //
    }
}
