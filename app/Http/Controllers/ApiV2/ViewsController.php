<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Views;
use App\Http\Requests\StoreViewsRequest;
use App\Http\Requests\UpdateViewsRequest;
use App\Http\Resources\ViewsResource;

class ViewsController extends Controller
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
            $views = Views::query();

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $views = $views->orderBy('id', $sort);
            }else {
                $views = $views->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $views = $views->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if (isset($request->category))
            {
                $views = $views->Where([ ['category', '=', $request->category] ]);
            }

            if (isset($request->type))
            {
                $views = $views->Where([ ['type', '=', $request->type] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $views = $views->paginate($perPage)->appends($request->query());

            if (count($views) < 1) {
                $msg = 'Vies Data Not Found';
            }

            // $data = $views;
            $data = ViewsResource::collection($views);

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
     * @param  \App\Http\Requests\StoreViewsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $save = Views::create([
                'parent_id'=> $request->parent_id,
                'type'     => $request->type,
                'category' => $request->category,
                'user_apps_id' => auth()->user()->id,
            ]);

            $data = $save;

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
     * @param  \App\Models\Views  $views
     * @return \Illuminate\Http\Response
     */
    public function show(Views $views)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateViewsRequest  $request
     * @param  \App\Models\Views  $views
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateViewsRequest $request, Views $views)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Views  $views
     * @return \Illuminate\Http\Response
     */
    public function destroy(Views $views)
    {
        //
    }
}
