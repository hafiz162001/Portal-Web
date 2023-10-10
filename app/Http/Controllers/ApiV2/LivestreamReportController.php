<?php

namespace App\Http\Controllers;

use App\Models\LivestreamReport;
use App\Http\Requests\StoreLivestreamReportRequest;
use App\Http\Requests\UpdateLivestreamReportRequest;

class LivestreamReportController extends Controller
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
     * @param  \App\Http\Requests\StoreLivestreamReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLivestreamReportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LivestreamReport  $livestreamReport
     * @return \Illuminate\Http\Response
     */
    public function show(LivestreamReport $livestreamReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLivestreamReportRequest  $request
     * @param  \App\Models\LivestreamReport  $livestreamReport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLivestreamReportRequest $request, LivestreamReport $livestreamReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LivestreamReport  $livestreamReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(LivestreamReport $livestreamReport)
    {
        //
    }
}
