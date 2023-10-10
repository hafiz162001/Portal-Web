<?php

namespace App\Http\Controllers;

use App\Models\CategoryArticle;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryArticleRequest;
use App\DataTables\CategoryArticleDataTable;

class CategoryArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryArticleDataTable $dataTable)
    {
        return $dataTable->render('category-article.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category-article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryArticleRequest $request)
    {

        try {
            $request->validated();
            $status = 'Category Article stored!';
            $success = true;
            $data = CategoryArticle::create($request->except('image'));
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
        return redirect()->route('category-article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\categoryArticle  $categoryArticle
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryArticle $categoryArticle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\categoryArticle  $categoryArticle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryArticle = CategoryArticle::find($id);
        return view('category-article.edit', compact('categoryArticle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\categoryArticle  $categoryArticle
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryArticleRequest $request, $id)
    {
        try {
            $request->validated();
            $status = 'Category Location updated!';
            $success = true;
            $categoryArticle = CategoryArticle::find($id);
            $categoryArticle->update($request->except('image'));
            if ($request->hasFile('image')) {
                $uploaded_image = $request->file('image');
                $extension = $uploaded_image->getClientOriginalExtension();
                $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                $filename = md5(microtime()) . '.' . $extension;
                $uploaded_image->move($destinationPath, $filename);
                $categoryArticle->image = $filename;
            }
            $categoryArticle->save();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('category-article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categoryArticle  $categoryArticle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $categoryArticle = CategoryArticle::find($id);
            if ($categoryArticle->delete()) {
                $status = "Category Location deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('category-article.index')->with('status', $status)->with('success', $success);
    }
}
