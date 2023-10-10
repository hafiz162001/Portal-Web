<?php

namespace App\Http\Controllers\ApiV2;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\MentorInterview;
use App\Http\Requests\StorementorInterviewRequest;
use App\Http\Requests\UpdatementorInterviewRequest;
use App\Http\Resources\MentorInterviewResource;

class MentorInterviewController extends Controller
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
            $mentorInterview = MentorInterview::query()->with('videos', 'thumbnail', 'mentor')->withCount('liked', 'miLike', 'isSaved')
            ->leftJoin(DB::raw("(SELECT * FROM mentors) mentor"), function ($join){
                                $join->on('mentor_interviews.id', '=', 'mentor.id');
                            })
            ->leftJoin(DB::raw("(SELECT count(1) as views, max(a.mentor_interview_id) as mentor_interview_id
                            from mentor_interview_views a
                            group by mentor_interview_id) b"), function ($join){
                                $join->on('mentor_interviews.id', '=', 'b.mentor_interview_id');
                            })
            ->leftJoin(DB::raw("(SELECT count(1) as likes, max(a.parent_id) as parent_id
                            from likes a
                            group by parent_id) c"), function ($join){
                                $join->on('mentor_interviews.id', '=', 'c.parent_id');
                            });

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $mentorInterview = $mentorInterview->orderBy('mentor_interviews.id', $sort);
            }

            if ($request->q) {
                $safetyFields = ['name', 'description'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $mentorInterview = $mentorInterview->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1)
            {
                $mentorInterview = $mentorInterview->Where([ ['mentor_interviews.highlight', '=', 1] ]);
            }

            if ($request->other && $request->other == 1 && !empty($request->id) && !empty($request->category_id))
            {
                $mentorInterview = $mentorInterview->Where([ ['mentor_interviews.id', '<>', $request->id],['mentor_interviews.category_id', '=', $request->category_id] ]);
            }

            if (isset($request->guest_star) && $request->guest_star == 1)
            {
                $mentorInterview = $mentorInterview->Where([ ['mentor.guest_star', '=', $request->guest_star] ]);
            }

            if (isset($request->mentor_id))
            {
                $mentorInterview = $mentorInterview->Where([ ['mentor_interviews.mentor_id', '=', $request->mentor_id] ]);
            }

            if (isset($request->id))
            {
                $mentorInterview = $mentorInterview->Where([ ['mentor_interviews.id', '=', $request->id] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $mentorInterview = $mentorInterview->paginate($perPage)->appends($request->query());

            if (count($mentorInterview) < 1) {
                $msg = 'Mentor Interviews Not Found';
            }

            // $data = $mentorInterview;
            $data = MentorInterviewResource::collection($mentorInterview);

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
     * @param  \App\Http\Requests\StorementorInterviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorementorInterviewRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\mentorInterview  $mentorInterview
     * @return \Illuminate\Http\Response
     */
    public function show(mentorInterview $mentorInterview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatementorInterviewRequest  $request
     * @param  \App\Models\mentorInterview  $mentorInterview
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatementorInterviewRequest $request, mentorInterview $mentorInterview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mentorInterview  $mentorInterview
     * @return \Illuminate\Http\Response
     */
    public function destroy(mentorInterview $mentorInterview)
    {
        //
    }
}
