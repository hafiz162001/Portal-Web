<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Gallery;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\ArticleDetail;
use App\DataTables\ArticleDataTable;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ArticleEditRequest;
use App\Models\CategoryArticle;
use App\Models\ChannelArticle;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleDataTable $dataTable)
    {
        $categories = Article::getCategories();
        $category = CategoryArticle::all();
        return $dataTable->render('article.index', compact('categories','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Article::getCategories();
        $category = CategoryArticle::all();
        $channels = ChannelArticle::all();
        $tags = Tag::all();
        return view('article.create', compact('categories','category','channels','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        try {
            $validatorImage = Validator::make($request->all(), [
                'extra_article_detail.*.images_article_detail'
                => 'max:10240|mimes:png,jpg,tif,gif,svg,jpeg,bmp,tiff,3gp,webm,wav,ogg,m4a,mp3,mp4,amr,aac,avi,wmv,mpg,mov,ogm,m4v',
            ]);
            $validatorDescription = Validator::make($request->all(), [
                'extra_article_detail.*.description' => 'required',
            ]);
            $validatorSortNum = Validator::make($request->all(), [
                'extra_article_detail.*.sort_num' => 'required',
            ]);

            if ($validatorImage->fails()) {
                return redirect()->back()->with('status', "The file must not be greater than 10 MB")->with('success', false);
            }

            if ($validatorDescription->fails()) {
                return redirect()->back()->with('status', "The description field is required")->with('success', false);
            }

            if ($validatorSortNum->fails()) {
                return redirect()->back()->with('status', "The sort number field is required")->with('success', false);
            }

            $status = 'Article stored!';
            $success = true;
            $article = Article::create($request->except('category_name','channel','category', 'images'));
            if ($article && $request->hasFile('images')) {
                foreach ($request->images as $key => $image) {
                    $uploaded_image = $image;
                    $extension = $uploaded_image->getClientOriginalExtension();
                    $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                    $filename = md5(microtime()) . '.' . $extension;
                    $uploaded_image->move($destinationPath, $filename);
                    $article->gambar = $filename;
                }
            }


            $article->category_id = $request->category;
            $article->channel_id = $request->channel;
            $stringg = implode(',', $request->tag);
            $article->tag_id = $stringg;
            $article->slug = $request->slug;
            $article->creator = $request->creator;
            $article->keterangan_gambar = $request->keterangan_gambar;
            $article->sumber_gambar = $request->sumber_gambar;
            $article->category_name = $request->category_name;
            $article->channel_name = $request->channel_name;
            $article->publish_date = now();
            // $article->tags()->attach($request->tag);
            // dd($article);
            $article->save();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('article.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {   $tags = Tag::all();
        $category = CategoryArticle::all();
        $channels = ChannelArticle::all();
        $tes = $article->tag_id;
        $images = $article->galleries->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'src' => asset('img/' . $item->image)
            ];
        });
        // $articleDetails = ArticleDetail::where('articles_id', $article->id)->orderBy('sort_num', 'asc')->get();
        // dd($articleDetails);
        return view('article.edit', compact('article', 'images','channels','category','tags','tes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleEditRequest $request, Article $article)
    {
        try {
            $validatorImage = Validator::make($request->all(), [
                'extra_article_detail.*.images_article_detail' =>
                'max:10240|mimes:png,jpg,tif,gif,svg,jpeg,bmp,tiff,3gp,webm,wav,ogg,m4a,mp3,mp4,amr,aac,avi,wmv,mpg,mov,ogm,m4v',
            ]);

            $validatorDescription = Validator::make($request->all(), [
                // 'extra_article_detail.*.description' => 'required',
            ]);
            $validatorSortNum = Validator::make($request->all(), [
                'extra_article_detail.*.sort_num' => 'required',
            ]);

            if ($validatorImage->fails()) {
                return redirect()->back()->with('status', "The file must not be greater than 10 MB")->with('success', false);
            }

            if ($validatorDescription->fails()) {
                return redirect()->back()->with('status', "The description field is required")->with('success', false);
            }

            if ($validatorSortNum->fails()) {
                return redirect()->back()->with('status', "The sort number field is required")->with('success', false);
            }

            $status = 'Article updated!';
            $success = true;
            $article->update($request->except('images','category','channel','tag'));

            // delete old image
            if (!empty($request->preloaded) || empty($request->preloaded)) {
                $oldGalleries = $article->galleries;
                $preloadedImages = $request->preloaded ?? [];
                foreach ($oldGalleries ?? [] as $key => $val) {
                    $oldId = $val->id;
                    $isRemoved = !in_array($oldId, $preloadedImages);
                    if ($isRemoved) {
                        // delete image
                        $val->delete();
                    }
                }
            }
            if ($article && $request->hasFile('images')) {
                foreach ($request->images as $key => $image) {
                    $uploaded_image = $image;
                    $extension = $uploaded_image->getClientOriginalExtension();
                    $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
                    $filename = md5(microtime()) . '.' . $extension;
                    $uploaded_image->move($destinationPath, $filename);
                    $article->gambar = $filename;
                }
            }

            // section add description &caption baru
            $new = [];
            $old = ArticleDetail::where('articles_id', $article->id)->pluck('id')->sortBy(['id', 'asc'])->all();

            $checkArticleNow = ArticleDetail::where('articles_id', $article->id);
            if (empty($request->extra_article_detail)) {
                // menghapus semua data
                foreach ($checkArticleNow->get() as $key => $value) {
                    $galleryArticleDetail = Gallery::where([['type', 'article_detail'], ['parent_id', $value->id]]);
                    $galleryArticleDetail ? $galleryArticleDetail->delete() : '';
                }
                $checkArticleNow->delete();
            }



            $article->category_id = $request->category;
            $article->channel_id = $request->channel;
            $stringg = implode(',', $request->tag);
            $article->tag_id = $stringg;

            $article->slug = $request->slug;
            $article->creator = $request->creator;
            $article->keterangan_gambar = $request->keterangan_gambar;
            $article->sumber_gambar = $request->sumber_gambar;
            $article->category_name = $request->category_name;
            $article->channel_name = $request->channel_name;
            $article->update();
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('article.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        try {
            $articleDetail = ArticleDetail::where('articles_id', $article->id);
            $gallery = Gallery::where('type', 'article')->where('parent_id', $article->id);
            if ($article->delete()) {
                foreach ($articleDetail->get() as $key => $value) {
                    $galleryArticleDetail = Gallery::where([['type', 'article_detail'], ['parent_id', $value->id]]);
                    $galleryArticleDetail ? $galleryArticleDetail->delete() : '';
                }
                $gallery ? $gallery->delete() : '';
                $articleDetail ? $articleDetail->delete() : '';

                $status = "Article deleted!";
                $success = true;
                Gallery::where('parent_id', $article->id)->where('type', 'article')->delete();
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('article.index')->with('status', $status)->with('success', $success);
    }
}
