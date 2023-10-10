<?php

namespace App\Http\Controllers;

use App\Models\Logger;
use App\Http\Requests\StoreLoggerRequest;
use App\Http\Requests\UpdateLoggerRequest;

class LoggerController extends Controller
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
     * @param  \App\Http\Requests\StoreLoggerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoggerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logger  $logger
     * @return \Illuminate\Http\Response
     */
    public function show(Logger $logger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoggerRequest  $request
     * @param  \App\Models\Logger  $logger
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoggerRequest $request, Logger $logger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logger  $logger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logger $logger)
    {
        //
    }
}
