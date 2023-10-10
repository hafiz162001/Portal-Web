<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
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

            $galleries_count = Gallery::where('type', 'evoria_galleries')->orWhere('type', 'evoria_gallery_videos')->count();
            $galleries = Gallery::query()->where('type', 'evoria_galleries')->orWhere('type', 'evoria_gallery_videos');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $galleries = $galleries->orderBy('id', $sort);
            }else {
                $galleries = $galleries->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $galleries = $galleries->where($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $galleries = $galleries->paginate($perPage)->appends($request->query());

            if (count($galleries) < 1) {
                $msg = 'Galleries Not Found';
            }

            // $data = $galleries;
            $data = GalleryResource::collection($galleries);

            $final_data = [
                'jumlah'    => $galleries_count,
                'galleries' => $data
            ];

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $final_data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),

        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLikeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $like = Like::where([ ['type', '=', $request->type], ['category', '=', $request->category], ['parent_id', '=', $request->parent_id] ])->where('user_apps_id', auth()->user()->id)->first();

            if ($like) {
                $like = $like->delete();
            }else{
                $like = Like::create([
                    'parent_id'=> $request->parent_id,
                    'type'     => $request->type,
                    'category' => $request->category,
                    'user_apps_id' => auth()->user()->id,
                    'recipient_user_apps_id' => $request->recipient_user_apps_id,
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
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLikeRequest  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLikeRequest $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Like $like)
    {
        //
    }
}
