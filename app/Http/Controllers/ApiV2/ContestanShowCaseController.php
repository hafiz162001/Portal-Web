<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ContestanShowCase;
use App\Models\ContestanShowCaseData;
use App\Models\ShowCaseMusicPlaying;
use App\Models\Contestan;
use App\Http\Requests\StoreContestanShowCaseRequest;
use App\Http\Requests\UpdateContestanShowCaseRequest;
use App\Http\Resources\ContestanShowCaseResource;
use Illuminate\Support\Facades\File;

class ContestanShowCaseController extends Controller
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
        $bypass = false;

        try {
            $category = 1;
            if (isset($request->category)) {
                if ($request->category == 50) {
                    $category = 50;
                }elseif ($request->category == 10) {
                    $category = 10;
                }
            }


            $showCase = ContestanShowCase::join('contestans', 'contestans.id', '=', 'contestan_show_cases.contestan_id')
            ->with([
                'video' => function($q) {
                    $q->join('contestan_show_cases', 'contestan_show_case_data.contestan_show_case_id', '=', 'contestan_show_cases.id')->where('contestan_show_cases.type', '=', 'video');
                },
                'music' => function($q) {
                    $q->join('contestan_show_cases', 'contestan_show_case_data.contestan_show_case_id', '=', 'contestan_show_cases.id')->where('contestan_show_cases.type', '=', 'music');
                },
                // 'contestan' => function($q) use($category){
                //     if ($category == 50) {
                //         $q->join('contestan_show_cases', 'b.contestan_id', '=', 'contestans.id')->where('contestans.status', '=', 1);
                //     }elseif ($category == 10) {
                //         $q->join('contestan_show_cases', 'b.contestan_id', '=', 'contestans.id')->where('contestans.big_ten', '=', 1);
                //     }
                // },
                'userApp', 'is_liked', 'is_saved', 'showCaseData',
            ])
            // ->leftJoin(DB::raw("(SELECT max(a.id) as scd_id , max(b.counter) as counter
            //         FROM contestan_show_cases a
            //         LEFT JOIN (select max(parent_id) as parent_id, max(type) as type, count(1) as counter
            //             from likes
            //             where deleted_at is null
            //             group by parent_id, type
            //         ) b on b.parent_id = a.id
            //         WHERE b.type = 'peserta_show_case'
            //         group by a.id) scd"), function ($join){
            //             $join->on('contestan_show_cases.id', '=', 'scd.scd_id');
            //         })
            ->withCount('liked', 'comment')
            ->orderByRaw('liked_count desc');
            // ->orderByRaw('scd.counter desc nulls last');


            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $showCase = $showCase->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['title', 'singer', 'writer', 'album_id', 'contestan_id', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'contestan_id' || $field == 'id' ) {
                            $showCase = $showCase->where($field, '=', $request->q);
                            $byPass = true;
                        }else {
                            $showCase = $showCase->where($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if (isset($category) && $category == 50) {
                $showCase = $showCase->where('contestans.status', '=', 1);
            }elseif (isset($category) && $category == 10) {
                $showCase = $showCase->where('contestans.big_ten', '=', 1);
            }

            if ($request->type == 'video') {
                $showCase = $showCase->where('contestan_show_cases.type', 'video');
            }elseif ($request->type == 'music') {
                $showCase = $showCase->where('contestan_show_cases.type', 'music');
            }else {
                if ($bypass == true) {
                    $showCase = $showCase->where('type', 'music');
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $showCase = $showCase->paginate($perPage)->appends($request->query());

            if (count($showCase) < 1) {
                $msg = 'Show Cases Not Found';
            }

            // $data = $showCase;
            $data = ContestanShowCaseResource::collection($showCase);

            if (isset($request->trend) && $request->trend == 1) {
                $data = [];
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
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContestanShowCaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'data';
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            // DB::beginTransaction();
            $contestan = ContestanShowCase::create($request->except(['file', 'cover']));
            $data = ContestanShowCaseResource::make($contestan);

            if ($request->file) {

                if ($request->type == 'music') {
                    // $uploaded_file = $request->file;
                    // $extension = $uploaded_file->getClientOriginalExtension();
                    // $filename = md5(microtime()) . '.' . $extension;
                    // $uploaded_file->move($destinationPath, $filename);
                    ContestanShowCaseData::updateOrCreate(
                        ['contestan_show_case_id' => $data->id],
                        ['file' => $request->file, 'user_apps_id' => auth()->user()->id, 'contestan_show_case_id' => $data->id]
                    );
                }

                elseif ($request->type == 'video') {
                    // if ($request->complete == 1) {
                    //     $ShowCaseData = ContestanShowCaseData::where('contestan_show_case_id', $data->id)->get();
                    //     foreach ($ShowCaseData as $key => $val) {
                    //         $base64 = $base64.$val->file;
                    //     }

                    //     $file      = $base64;
                    //     $parts     = explode(";base64,", $file);
                    //     $fileparts = explode("video/", @$parts[0]);
                    //     $filetype  = $fileparts[1];
                    //     $fileName  = md5(microtime()). '.' . $filetype;
                    //     \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                    // }else {
                        ContestanShowCaseData::create(
                            ['file' => $request->file, 'user_apps_id' => auth()->user()->id, 'contestan_show_case_id' => $data->id]
                        );
                    // }
                }

            }

            if ($request->cover) {
                $file      = $request->cover;
                $parts     = explode(";base64,", $file);
                $fileparts = explode("image/", @$parts[0]);
                $filetype  = $fileparts[1];
                $fileName  = md5(microtime()). '.' . $filetype;
                \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                ContestanShowCase::where('id', $data->id)->update(
                    ['cover' => $fileName]
                );
            }

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

    public function getExtension($status = 'mp4'){
        $label['x-msvideo']  = 'avi';
        $label['x-matroska'] = 'mkv';
        $label['x-matroska'] = 'mp4';

        $label[$status];
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContestanShowCase  $contestanShowCase
     * @return \Illuminate\Http\Response
     */
    public function show(ContestanShowCase $contestanShowCase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContestanShowCaseRequest  $request
     * @param  \App\Models\ContestanShowCase  $contestanShowCase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContestanShowCaseRequest $request, ContestanShowCase $contestanShowCase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContestanShowCase  $contestanShowCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContestanShowCase $contestanShowCase)
    {
        //
    }

    public function showCaseMusicPlaying(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            // DB::beginTransaction();

            ShowCaseMusicPlaying::create(
                ['user_apps_id' => auth()->user()->id, 'contestan_show_case_id' => $request->contestan_show_case_id]
            );

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

    public function lastListening(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 25;

        try {

            $showCase = ContestanShowCase::query()->where('type', 'music')->with('music');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $showCase = $showCase->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['title', 'singer', 'writer'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $showCase = $showCase->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $showCase = $showCase->paginate($perPage)->appends($request->query());

            if (count($showCase) < 1) {
                $msg = 'Show Cases Not Found';
            }

            $data = ContestanShowCaseResource::collection($showCase);

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

    public function diRekomendasikan(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 25;

        try {

            $showCase = ContestanShowCase::query()->where('type', 'music')->with('music');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $showCase = $showCase->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['title', 'singer', 'writer'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $showCase = $showCase->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $showCase = $showCase->paginate($perPage)->appends($request->query());

            if (count($showCase) < 1) {
                $msg = 'Show Cases Not Found';
            }

            $data = ContestanShowCaseResource::collection($showCase);

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

    public function showCasefifty(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 50;
        $bypass = false;

        try {
            $showCase = Contestan::join('contestan_show_cases', 'contestan_show_case.contestan_show_case_id')->
            query()->with([
                'video' => function($q) {
                    $q
                    ->join('contestan_show_cases', 'contestan_show_case_data.contestan_show_case_id', '=', 'contestan_show_cases.id')->where('contestan_show_cases.type', '=', 'video');
                },
                'music' => function($q) {
                    $q
                    ->join('contestan_show_cases', 'contestan_show_case_data.contestan_show_case_id', '=', 'contestan_show_cases.id')->where('contestan_show_cases.type', '=', 'music');
                },
                'contestan', 'userApp', 'is_liked', 'is_saved', 'showCaseData',
            ])->withCount('liked', 'comment');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $showCase = $showCase->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['title', 'singer', 'writer', 'album_id', 'contestan_id', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'contestan_id' || $field == 'id' ) {
                            $showCase = $showCase->where($field, '=', $request->q);
                            $byPass = true;
                        }else {
                            $showCase = $showCase->where($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if ($request->type == 'video') {
                $showCase = $showCase->where('type', 'video');
            }elseif ($request->type == 'music') {
                $showCase = $showCase->where('type', 'music');
            }else {
                if ($bypass == true) {
                    $showCase = $showCase->where('type', 'music');
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $showCase = $showCase->paginate($perPage)->appends($request->query());

            if (count($showCase) < 1) {
                $msg = 'Show Cases Not Found';
            }

            // $data = $showCase;
            $data = ContestanShowCaseResource::collection($showCase);

            if (isset($request->trend) && $request->trend == 1) {
                $data = [];
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
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

}
