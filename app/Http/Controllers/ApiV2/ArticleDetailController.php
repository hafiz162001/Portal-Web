<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreArticleDetailRequest;
use App\Http\Requests\UpdateArticleDetailRequest;
use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\ArticleResource;
use App\Models\ArticleDetail;
use App\Models\Article;
use App\Models\Galleries;
use App\Models\Views;

class ArticleDetailController extends Controller
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
        $perPage = 4;

        try {
            $article_detail = ArticleDetail::query()->with('galleries');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'desc' : $request->sort;

                $article_detail = $article_detail->orderBy('id', $sort);
            }else {
                $article_detail = $article_detail->orderBy('id', 'asc');
            }

            if ($request->q) {
                $safetyFields = ['title', 'subtitle', 'publisher'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $article_detail = $article_detail->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1){
                $article_detail = $article_detail->Where([ ['highlight', '=', 1] ]);
            }

            if (isset($request->article_id)){
                $article_detail = $article_detail->Where([ ['articles_id', '=', $request->article_id] ]);
            }

            if (isset($request->other) && $request->other == 1 && !empty($request->id) && !empty($request->category_id)){
                $article_detail = $article_detail->Where([ ['id', '<>', $request->id],['category_id', '=', $request->category_id] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $article_detail = $article_detail->paginate($perPage)->appends($request->query());

            if (count($article_detail) < 1) {
                $msg = 'Article Detail Not Found';
            }

            $articles = Article::query()->with('is_liked', 'galleries')->withCount('views', 'comment', 'liked');
            if (isset($request->article_id)){
                $articles = $articles->Where([ ['id', '=', $request->article_id] ]);
            }
            $articles = $articles->paginate($perPage)->appends($request->query());

            $dataArticles = $articles;
            // $data = $article_detail;
            $data = ArticleDetailResource::collection($article_detail);


            //views
            $save = Views::create([
                'parent_id'=> $request->article_id,
                'type'     => 'article',
                'category' => 'article',
                'user_apps_id' => auth()->user()->id,
            ]);


        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success'       => $success,
            'data'          => $data,
            'liked_count'   => $dataArticles[0]->liked_count,
            'comment_count' => $dataArticles[0]->comment_count,
            'views_count'   => $dataArticles[0]->views_count,
            'is_liked'      => $dataArticles[0]->is_liked,
            'message'       => $msg,
            'nextUrl'       => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl'       => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ArticleDetail  $articleDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleDetail $articleDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleDetailRequest  $request
     * @param  \App\Models\ArticleDetail  $articleDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleDetailRequest $request, ArticleDetail $articleDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ArticleDetail  $articleDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleDetail $articleDetail)
    {
        //
    }
}
