<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;

class ProgramController extends Controller
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
            $program = Program::query()->with([
                'galleries',
                'descriptions',
                // 'descriptions' => function($q) {
                //     $q
                //     ->join('programs', 'programs.id', '=', 'descriptions.parent_id')->where('descriptions.type', 'program');
                //     // ->join('tickets', 'tickets.id', '=', 'descrioptions.ticket_id')->where('tickets.category', 'evoria');
                // }
                ]);
            // ->join('descriptions', function ($join) {
            //     $join->on('descriptions.parent_id', '=', 'programs.id');
            //     $join->where('descriptions.type', '=', 'program');
            // });

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $program = $program->orderBy('id', $sort);
            }else {
                $program = $program->orderBy('sort', 'asc');
            }

            if ($request->q) {
                $safetyFields = ['name', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'id') {
                            $program = $program->where($field, '=', $request->q);
                        }else {
                            $program = $program->where($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            // $date = date('Y-m-d');
            // if (isset($request->type) &&ProgramResource $request->type == 'this') {
            //     $program = $program->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
            // }elseif (isset($request->type) && $request->type == 'next') {
            //     $program = $program->whereDate('start_date', '>=', $date);
            // }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $program = $program->paginate($perPage)->appends($request->query());

            if (count($program) < 1) {
                $msg = 'Program Cases Not Found';
            }

            $data = $program;
            $data = ProgramResource::collection($program);

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
     * @param  \App\Http\Requests\StoreProgramRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProgramRequest  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //
    }
}
