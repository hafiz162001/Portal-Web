<?php

namespace App\Http\Controllers;

use App\Models\Mentoring;
use App\Http\Requests\StoreMentoringRequest;
use App\Http\Requests\UpdateMentoringRequest;

class MentoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMentoringRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMentoringRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mentoring  $mentoring
     * @return \Illuminate\Http\Response
     */
    public function show(Mentoring $mentoring)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMentoringRequest  $request
     * @param  \App\Models\Mentoring  $mentoring
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMentoringRequest $request, Mentoring $mentoring)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mentoring  $mentoring
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mentoring $mentoring)
    {
        //
    }
}
