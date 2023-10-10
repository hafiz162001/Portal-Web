<?php

namespace App\Http\Controllers;

use App\Models\ChannelArticle;
use Illuminate\Http\Request;
use App\Http\Requests\ChannelArticleRequest;
use App\DataTables\ChannelArticleDataTable;

class ChannelArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ChannelArticleDataTable $dataTable)
    {
        return $dataTable->render('channel-article.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channelArticle = ChannelArticle::all();
        return view('channel-article.create',compact('channelArticle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChannelArticleRequest $request)
    {

        try {
            $request->validated();
            $status = 'Category Article stored!';
            $success = true;
            $data = ChannelArticle::create($request->except('image'));
            if ($request->hasFile('image')) {
                $uploaded_image = $request->file('image');
                $extension = $uploaded_image->getClientOriginalExtension();
                $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                $filename = md5(microtime()) . '.' . $extension;
                $uploaded_image->move($destinationPath, $filename);
                $data->image = $filename;
            }
            $data->save();
        } catch (\Throwable $e) {

            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('channel-article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryLocation  $categoryLocation
     * @return \Illuminate\Http\Response
     */
    public function show(ChannelArticle $categoryLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryLocation  $categoryLocation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $channelArticle = ChannelArticle::find($id);
        $channelArticleAll = ChannelArticle::all();
        return view('channel-article.edit', compact('channelArticle','channelArticleAll'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryLocation  $categoryLocation
     * @return \Illuminate\Http\Response
     */
    public function update(ChannelArticleRequest $request, $id)
    {
        try {
            $request->validated();
            $status = 'Category Location updated!';
            $success = true;
            $categoryLocation = ChannelArticle::find($id);
            $categoryLocation->update($request->except('image'));
            if ($request->hasFile('image')) {
                $uploaded_image = $request->file('image');
                $extension = $uploaded_image->getClientOriginalExtension();
                $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                $filename = md5(microtime()) . '.' . $extension;
                $uploaded_image->move($destinationPath, $filename);
                $categoryLocation->image = $filename;
            }
            $categoryLocation->save();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('category-lct.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryLocation  $categoryLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $categoryLocation = ChannelArticle::find($id);
            if ($categoryLocation->delete()) {
                $status = "Channel Article deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('channel-article.index')->with('status', $status)->with('success', $success);
    }
}
