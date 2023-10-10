<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Mentor;
use App\Http\Requests\StoreMentorRequest;
use App\Http\Requests\UpdateMentorRequest;
use App\Http\Resources\MentorResource;

class MentorController extends Controller
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
            $mentor = Mentor::query()->with('galleries', 'galleriesProfile');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $mentor = $mentor->orderBy('id', $sort);
            }else {
                $mentor = $mentor->orderBy('sort', 'ASC');
            }

            if ($request->q) {
                $safetyFields = ['name'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $mentor = $mentor->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1)
            {
                $mentor = $mentor->Where([ ['highlight', '=', 1] ]);
            }

            if ($request->my_profile && $request->my_profile == 1)
            {
                $mentor = $mentor->Where([ ['user_apps_id', '=', auth()->user()->id] ]);
            }

            if (isset($request->guest_star) && $request->guest_star == 1)
            {
                $mentor = $mentor->Where([ ['guest_star', '=', $request->guest_star] ]);
            }

            if ($request->other && $request->other == 1 && !empty($request->id) && !empty($request->category_id))
            {
                $mentor = $mentor->Where([ ['id', '<>', $request->id],['category_id', '=', $request->category_id] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $mentor = $mentor->paginate($perPage)->appends($request->query());

            if (count($mentor) < 1) {
                $msg = 'Mentor Not Found';
            }

            // $data = $mentor;
            $data = MentorResource::collection($mentor);

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
     * @param  \App\Http\Requests\StoreMentorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMentorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mentor  $mentor
     * @return \Illuminate\Http\Response
     */
    public function show(Mentor $mentor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMentorRequest  $request
     * @param  \App\Models\Mentor  $mentor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMentorRequest $request, Mentor $mentor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mentor  $mentor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mentor $mentor)
    {
        //
    }
}
