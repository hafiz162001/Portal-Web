<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StorePesertaRequest;
use App\Http\Requests\UpdatePesertaRequest;
use App\Http\Resources\MentorInterviewResource;
use App\Http\Resources\ContestanResource;
use App\Models\Peserta;
use App\Models\Contestan;
use App\Models\Gallery;
use App\Models\UserApps;
use Illuminate\Support\Facades\File;


class PesertaController extends Controller
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
        $perPage = 5;

        try {
            $contestan = Contestan::query()->with('galleries', 'coverGalleries', 'styleMusic')->withCount('music', 'albums')->where('status', 0);

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $contestan = $contestan->orderBy('id', $sort);
            }else {
                $contestan = $contestan->orderByDesc('id');
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $contestan = $contestan->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1)
            {
                $contestan = $contestan->Where([ ['highlight', '=', 1] ]);
            }

            if ($request->my_profile && $request->my_profile == 1)
            {
                $contestan = $contestan->Where([ ['user_apps_id', '=', auth()->user()->id] ]);
            }

            if ($request->other && $request->other == 1 && !empty($request->id) && !empty($request->category_id))
            {
                $contestan = $contestan->Where([ ['id', '<>', $request->id],['category_id', '=', $request->category_id] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $contestan = $contestan->paginate($perPage)->appends($request->query());

            if (count($contestan) < 1) {
                $msg = 'Contestan Not Found';
            }

            // $data = $contestan;
            $data = ContestanResource::collection($contestan);

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
     * @param  \App\Http\Requests\StorePesertaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePesertaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function show(Peserta $peserta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePesertaRequest  $request
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePesertaRequest $request, Peserta $peserta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peserta $peserta)
    {
        //
    }
}
