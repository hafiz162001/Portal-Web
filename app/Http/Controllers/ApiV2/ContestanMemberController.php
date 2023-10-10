<?php

namespace App\Http\Controllers\ApiV2 ;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ContestanMember;
use App\Http\Requests\StoreContestanMemberRequest;
use App\Http\Requests\UpdateContestanMemberRequest;
use App\Http\Resources\ContestanMembersResource;

class ContestanMemberController extends Controller
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
        $perPage = 4;

        try {
            $contestanMember = ContestanMember::query();

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'desc' : $request->sort;

                $contestanMember = $contestanMember->orderBy('id', $sort);
            }else {
                $contestanMember = $contestanMember->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['phone'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $contestanMember = $contestanMember->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1)
            {
                $contestanMember = $contestanMember->Where([ ['highlight', '=', 1] ]);
            }

            if ($request->category)
            {
                $contestanMember = $contestanMember->Where([ ['category', '=', $request->category] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $contestanMember = $contestanMember->paginate($perPage)->appends($request->query());

            if (count($contestanMember) < 1) {
                $msg = 'Articles Not Found';
            }

            // $data = $contestanMember;
            $data = ContestanMembersResource::collection($contestanMember);

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
     * @param  \App\Http\Requests\StoreContestanMemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $insert = ContestanMember::insert([
                'contestan_id' => $request->contestan_id,
                'phone'        => $request->phone,
                'jabatan'      => $request->jabatan,
                'status'       => 0
            ]);

            $data = $insert;

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
     * @param  \App\Models\ContestanMember  $contestanMember
     * @return \Illuminate\Http\Response
     */
    public function show(ContestanMember $contestanMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContestanMemberRequest  $request
     * @param  \App\Models\ContestanMember  $contestanMember
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContestanMemberRequest $request, ContestanMember $contestanMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContestanMember  $contestanMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContestanMember $contestanMember)
    {
        //
    }
}
