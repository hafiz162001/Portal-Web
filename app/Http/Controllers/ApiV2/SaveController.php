<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Save;
use App\Http\Requests\StoreSaveRequest;
use App\Http\Requests\UpdateSaveRequest;
use App\Http\Resources\SaveResource;

class SaveController extends Controller
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
            $save = Save::query();

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $save = $save->orderBy('id', $sort);
            }else {
                $save = $save->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $save = $save->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if (isset($request->category))
            {
                $save = $save->Where([ ['category', '=', $request->category] ]);
            }

            if (isset($request->type))
            {
                $save = $save->Where([ ['type', '=', $request->type] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $save = $save->paginate($perPage)->appends($request->query());

            if (count($save) < 1) {
                $msg = 'Saved Data Not Found';
            }

            // $data = $save;
            $data = SaveResource::collection($save);

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
     * @param  \App\Http\Requests\StoreSaveRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $save = Save::where([ ['type', '=', $request->type], ['category', '=', $request->category], ['parent_id', '=', $request->parent_id] ])->where('user_apps_id', auth()->user()->id)->first();

            if ($save) {
                $save = $save->delete();
            }else{
                $save = Save::create([
                    'parent_id'=> $request->parent_id,
                    'type'     => $request->type,
                    'category' => $request->category,
                    'user_apps_id' => auth()->user()->id,
                ]);
            }

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
     * @param  \App\Models\Save  $save
     * @return \Illuminate\Http\Response
     */
    public function show(Save $save)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSaveRequest  $request
     * @param  \App\Models\Save  $save
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSaveRequest $request, Save $save)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Save  $save
     * @return \Illuminate\Http\Response
     */
    public function destroy(Save $save)
    {
        //
    }
}
