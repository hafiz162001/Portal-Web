<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Location;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\LocationResource;
use App\Http\Resources\GroupResource;
use App\Models\Galleries;

class GroupController extends Controller
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

            $group = Group::query()->with('galleries')->orderBy('category')->orderBy('index');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $group = $group->orderBy('id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $group = $group->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if(isset($request->bloc_id)){
                $group = $group->Where('bloc_id', '=', $request->bloc_id);
            }

            if(isset($request->category) && $request->category != ''){
                $group = $group->Where('category_apps', '=', $request->category);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $group = $group->paginate($perPage)->appends($request->query());

            if (count($group) < 1) {
                $msg = 'Groups Not Found';
            }

            $data = GroupResource::collection($group);
            // $groupedData = $data->groupBy('type');
            // $groupedData->all();

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $isXCollaborator = false;
        if(!empty($request) && !empty($request->category) && $request->category == "x_collaborator"){
            $isXCollaborator = true;
        }
        $resp = [
            'success' => $success,
            'data'    => $isXCollaborator ? [] : $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    // public function index(Request $request)
    // {
    //     $success = true;
    //     $data = [];
    //     $msg = 'Ok';
    //     $perPage = 4;

    //     try {
    //         $location = Location::get();

    //         // $data = $location;
    //         $data = LocationResource::collection($location);
    //         $data = $data->groupBy('type');

    //     } catch (\Throwable $th) {
    //         $success = false;
    //         $msg = $th->getMessage();
    //         // $msg = config('app.error_message');
    //     }

    //     $resp = [
    //         'success' => $success,
    //         'data'    => $data,
    //         'message' => $msg,
    //         // 'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
    //         // 'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
    //     ];

    //     return $resp;
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
