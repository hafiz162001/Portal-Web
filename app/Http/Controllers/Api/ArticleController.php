<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleDetail;
use App\Models\Galleries;

class ArticleController extends Controller
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
        $category = 'blocx';
        $type = 'article';

        try {
            if (isset($request->type)) {
                $type = $request->type;
            }

            if (isset($request->category)){
                $category = $request->category;
            }

            $articles = Article::query()->with('galleries', 'article_details')->with(['linkRedirect' => function($q) use($category) {
                $q->where('ref_type', '=', $category);
            }])->withCount([
                'is_liked' => function($q) use($category, $type) {
                    $q
                    ->where('likes.type', '=', $type)->where('likes.category', '=', $category);
                },
                'liked' => function($q) use($category, $type) {
                    $q
                    ->where('likes.type', '=', $type)->where('likes.category', '=', $category);
                },
                'comment' => function($q) use($category, $type) {
                    $q
                    ->where('comments.type', '=', $type)->where('comments.category', '=', $category);
                },

            ]);

            if (isset($request->article_id)){
                $articles = $articles->Where([ ['id', '=', $request->article_id] ]);
            }

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'desc' : $request->sort;

                $articles = $articles->orderBy('id', $sort);
            }else {
                $articles = $articles->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['title', 'subtitle', 'publisher'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $articles = $articles->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if ($request->highlight && $request->highlight == 1){
                $articles = $articles->Where([ ['highlight', '=', 1] ]);
            }

            if (isset($request->other) && $request->other == 1 && !empty($request->id) && !empty($request->category_id)){
                $articles = $articles->Where([ ['id', '<>', $request->id],['category_id', '=', $request->category_id] ]);
            }

            if (isset($request->category)){
                $articles = $articles->Where([ ['category', '=', $request->category] ]);

                if (isset($request->type)) {
                    $articles = $articles->Where([ ['type', '=', $request->type] ]);
                }else {
                    $articles = $articles->Where([ ['type', '!=', 'article'] ]);
                }
            }else {
                $articles = $articles->Where([ ['category', '=', 'blocx'] ]);

                if (isset($request->type) && $type == 'kutipan') {
                    $articles = $articles->Where([ ['type', '=', $type] ]);
                }
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $articles = $articles->paginate($perPage)->appends($request->query());

            if (count($articles) < 1) {
                $msg = 'Articles Not Found';
            }

            // $data = $articles;
            $data = ArticleResource::collection($articles);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
