<?php

namespace App\Http\Controllers;

use App\DataTables\TagArticleDataTable;
use App\Http\Requests\TagArticleRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index(TagArticleDataTable $dataTable)
     {
         return $dataTable->render('tag-article.index');
     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tag-article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagArticleRequest $request)
    {

        try {
            $request->validated();
            $status = 'Tag Article stored!';
            $success = true;
            $data = Tag::create($request->except('image'));
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
        return redirect()->route('tag-article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tagArticle = Tag::find($id);
        return view('tag-article.edit', compact('tagArticle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagArticleRequest $request,$id)
    {
        try {
            $request->validated();
            $status = 'Tag Artiicle updated!';
            $success = true;
            $categoryArticle = Tag::find($id);
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
        return redirect()->route('tag-article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $tagArticle = Tag::find($id);
            if ($tagArticle->delete()) {
                $status = "Tag Artcile deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('tag-article.index')->with('status', $status)->with('success', $success);
    }
    
}
