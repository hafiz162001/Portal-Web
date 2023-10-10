<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\DataTables\BannerDataTable;
use App\Http\Requests\BannerRequest;
use App\Models\CategoryArticle;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BannerDataTable $dataTable)
    {
        
        $banner = Banner::all();
        return $dataTable->render('banner.index', compact('banner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = CategoryArticle::all();
        return view('banner.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        try {
            $request->validated();
            $status = 'Banner stored!';
            $success = true;
            $banner = Banner::create($request->except('file','category'));
            if ($request->hasFile('file')) {
                $uploaded_image = $request->file('file');
                $extension = $uploaded_image->getClientOriginalExtension();
                $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                $filename = md5(microtime()) . '.' . $extension;
                $uploaded_image->move($destinationPath, $filename);
                $banner->file = $filename;
            }
            // $banner->category_id = $request->category;
            $banner->save();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('banner.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $category = CategoryArticle::all();
        $types = Banner::getTypes();
        return view('banner.edit', compact('banner', 'types','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        try {
            $request->validated();
            $status = 'Banner updated!';
            $success = true;
            $banner->update($request->except('file','type','category'));
            if ($request->hasFile('file')) {
                $uploaded_image = $request->file('file');
                $extension = $uploaded_image->getClientOriginalExtension();
                $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                $filename = md5(microtime()) . '.' . $extension;
                $uploaded_image->move($destinationPath, $filename);
                $banner->file = $filename;
            }
            // $banner->category_id = $request->category;
            $banner->update();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('banner.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        try {
            if ($banner->delete()) {
                $status = "Banner deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('banner.index')->with('status', $status)->with('success', $success);
    }
}
