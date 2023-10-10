<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ContestanShowCaseData;
use App\Http\Requests\StoreContestanShowCaseDataRequest;
use App\Http\Requests\UpdateContestanShowCaseDataRequest;
use App\Http\Resources\ContestanShowCaseDataResource;

class ContestanShowCaseDataController extends Controller
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
     * @param  \App\Http\Requests\StoreContestanShowCaseDataRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContestanShowCaseData  $contestanShowCaseData
     * @return \Illuminate\Http\Response
     */
    public function show(ContestanShowCaseData $contestanShowCaseData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContestanShowCaseDataRequest  $request
     * @param  \App\Models\ContestanShowCaseData  $contestanShowCaseData
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContestanShowCaseDataRequest $request, ContestanShowCaseData $contestanShowCaseData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContestanShowCaseData  $contestanShowCaseData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContestanShowCaseData $contestanShowCaseData)
    {
        //
    }
}
