<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Gallery;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Http\Resources\AlbumResource;
use Illuminate\Support\Facades\File;

class AlbumController extends Controller
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

            $album = Album::query()->with('galleries', 'user', 'contestan');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $album = $album->orderBy('id', $sort);
            }elseif(isset($request->populer) && $request->populer == 1){
                $album = $album->leftJoin(DB::raw("(
                    SELECT max(a.album_id) as album_idnya , count(b.counter) as counter
                        FROM contestan_show_cases a
                        LEFT JOIN (
                            SELECT max(a.parent_id) as parent_id, max(a.type) as type, count(1) as counter
                                FROM likes a
                                WHERE a.deleted_at is null
                                GROUP BY a.parent_id, a.type
                        ) b ON b.parent_id = a.id
                        WHERE b.type = 'peserta_show_case'
                        GROUP BY album_id
                ) b"), function ($join){
                    $join->on('b.album_idnya', '=', 'albums.id');
                })->orderByDesc('counter');
            }

            if ($request->q) {
                $safetyFields = ['name', 'contestan_id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'contestan_id') {
                            $album = $album->Where($field, '=', $request->q);
                        }else {
                            $album = $album->Where($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $album = $album->paginate($perPage)->appends($request->query());

            if (count($album) < 1) {
                $msg = 'Album Not Found';
            }

            $data = AlbumResource::collection($album);

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
     * @param  \App\Http\Requests\StoreAlbumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {

            $album = Album::create($request->except(['images']));

            $data = AlbumResource::make($album);
            if ($request->images) {
                $file      = $request->images;
                $parts     = explode(";base64,", $file);
                $fileparts = explode("image/", @$parts[0]);
                $filetype  = $fileparts[1];
                $fileName  = md5(microtime()). '.' . $filetype;
                \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                Gallery::updateOrCreate(
                    ['parent_id' => $data->id, 'type' => 'album'],
                    ['image' => $fileName, 'type' => 'album', 'parent_id' => $data->id]
                );
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
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAlbumRequest  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        //
    }
}
