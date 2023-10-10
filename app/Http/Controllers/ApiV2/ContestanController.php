<?php

namespace App\Http\Controllers\ApiV2 ;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ContestanRequest;
use App\Http\Requests\StoreContestanRequest;
use App\Http\Requests\UpdateContestanRequest;
use App\Http\Resources\ContestanResource;
use App\Models\Contestan;
use App\Models\ContestanShowCase;
use App\Models\ContestanShowCaseData;
use App\Models\Gallery;
use App\Models\UserApps;
use Illuminate\Support\Facades\File;

class ContestanController extends Controller
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
            $contestan = Contestan::query()->with('galleries', 'coverGalleries', 'mentoring', 'styleMusic', 'typeContestan')->withCount('music', 'albums', 'likes')
            // ->leftjoin(DB::raw("(SELECT max(a.id) as id , count(b.counter) as counter
            //         FROM contestans a
            //         LEFT JOIN (select max(parent_id) as parent_id, max(type) as type, count(1) as counter
            //             from likes
            //             where deleted_at is null
            //             group by parent_id, type
            //         ) b on b.parent_id = a.id
            //         WHERE b.type = 'peserta_show_case'
            //         group by a.id) b"), function ($join){
            //                 $join->on('contestans.id', '=', 'b.id');
            //             })

            ->orderByRaw('likes_count desc');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $contestan = $contestan->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['name', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'id') {
                            $contestan = $contestan->where('contestans.'.$field, '=', $request->q);
                        }else {
                            $contestan = $contestan->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                        }
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

            if (isset($request->category))
            {
                if ($request->category == 50) {
                    $contestan = $contestan->Where([ ['status', '=', 1]]);
                }elseif ($request->category == 10) {
                    $contestan = $contestan->Where([ ['big_ten', '=', 1]]);
                }
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
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
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
     * @param  \App\Http\Requests\StoreContestanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 10;

        try {
            if (isset($post['ktp'])) {
                UserApps::where('id', auth()->user()->id)->update([
                    'ktp' => $post['ktp']
                ]);
            }

            $update = Contestan::updateOrCreate(
                ['user_apps_id' => auth()->user()->id],
                [
                    'name' => $request->name,
                    'biodata' => $request->biodata,
                    'type_id' => 2,
                    'type' => $request->type_id,
                    // 'status' => 1,
                    // 'origin' => $request->origin,
                    // 'user_apps_id' => auth()->user()->id,
                    'jumlah_penampil' => $request->jumlah_penampil,
                    'style_music' => $request->style_music_id,
                    // 'style_music_id' => $request->style_music_id,
                    'address' => $request->address,
                    // 'link_social_media' => $request->link_social_media,
                    // 'link_audo_video' => $request->link_audo_video,
                    'nama_management' => $request->nama_management,
                    'hubungan_pendaftar' => $request->hubungan_pendaftar
                ]
            );

            $data = ContestanResource::make($update);

            if ($request->images) {
                $file      = $request->images;
                $parts     = explode(";base64,", $file);
                $fileparts = explode("image/", @$parts[0]);
                $filetype  = $fileparts[1];
                $fileName  = md5(microtime()). '.' . $filetype;
                \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                Gallery::updateOrCreate(
                    ['parent_id' => $data->id, 'type' => 'contestan'],
                    ['image' => $fileName, 'type' => 'contestan', 'parent_id' => $data->id]
                );
            }

            if ($request->cover_images) {
                $file      = $request->cover_images;
                $parts     = explode(";base64,", $file);
                $fileparts = explode("image/", @$parts[0]);
                $filetype  = $fileparts[1];
                $fileName  = md5(microtime()). '.' . $filetype;
                \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                Gallery::updateOrCreate(
                    ['parent_id' => $data->id, 'type' => 'cover_contestan'],
                    ['image' => $fileName, 'type' => 'cover_contestan', 'parent_id' => $data->id]
                );
            }

            if(isset($request->link_audo_video)){
                $link = $request->link_audo_video;
                $type = '';
                $pendaftar = Contestan::where('user_apps_id', auth()->user()->id)->first();
                if (str_contains($link, 'youtu.be')) {
                    $type = 'video';
                }elseif (str_contains($link, 'youtube.com')) {
                    $type = 'video';
                }elseif (str_contains($link, '.mp3')) {
                    $type = 'music';
                }elseif (str_contains($link, ',mp4')) {
                    $type = 'video';
                }

                if ($pendaftar) {
                    $insert_pendaftar = ContestanShowCase::create([
                        'contestan_id' => $pendaftar->id,
                        'user_apps_id' => auth()->user()->id,
                        'tile'  => $pendaftar->name,
                        'single' => $pendaftar->name,
                        'writen' => $pendaftar->name,
                        'produce' => $pendaftar->nama_management,
                        'description' => '',
                        'type'  => $type,
                        'status' => 1,
                    ]);

                    if ($insert_pendaftar) {
                        $insert_data_pendaftar = ContestanShowCaseData::create([
                            'contestan_show_case_id' => $insert_pendaftar->id,
                            'user_apps_id' => auth()->user()->id,
                            'file' => $link,
                        ]);
                    }
                }
            }

        } catch (\Exception $th) {
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
     * @param  \App\Models\Contestan  $contestan
     * @return \Illuminate\Http\Response
     */
    public function show(Contestan $contestan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContestanRequest  $request
     * @param  \App\Models\Contestan  $contestan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContestanRequest $request, Contestan $contestan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contestan  $contestan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contestan $contestan)
    {
        //
    }
}
