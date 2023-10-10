<?php

namespace App\Http\Controllers\Api ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
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
        $perPage = 5;

        try {
            $category = 'blocx';
            $type = 'article';
            $uid = auth()->user()->id;

            if (isset($request->category)) {
                $category = $request->category;
            }

            if (isset($request->type)) {
                $type = $request->type;
                if (isset($request->recipient_user_apps_id)) {
                    $type = $request->type.'_comment';
                }
            }

            $comment = Comment::query()->with('user')->withCount([
                'likeComment' => function($q) use($category, $type) {
                    $q
                    ->where('likes.type', '=', $type)->where('likes.category', '=', $category);
                },
                'isLiked' => function($q) use($category, $type, $uid) {
                    $q
                    ->where('likes.type', '=', $type)->where('likes.category', '=', $category)->where('likes.user_apps_id', $uid);
                },
                'anotherComment' => function($q) use($category, $type) {
                    $q
                    ->where('comments.type', '=', $type)->where('comments.category', '=', $category);
                },
            ]);

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $comment = $comment->orderBy('id', $sort);
            }else {
                $comment = $comment->orderBy('id', 'desc');
            }

            if (isset($request->category)) {
                $comment = $comment->where('category', $request->category);
            }

            if (isset($request->type)) {
                $comment = $comment->where('type', $request->type);
            }

            if (isset($request->parent_id)) {
                $comment = $comment->where('parent_id', $request->parent_id);
            }

            if (isset($request->recipient_user_apps_id)) {
                $comment = $comment->where('recipient_user_apps_id', $request->recipient_user_apps_id);
            }else {
                $comment = $comment->where('recipient_user_apps_id', null);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $comment = $comment->paginate($perPage)->appends($request->query());

            if (count($comment) < 1) {
                $msg = 'Comment Not Found';
            }

            // $data = $comment;
            $data = CommentResource::collection($comment);

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
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $comment = Comment::create([
                'parent_id' => $request->parent_id,
                'category' => $request->category,
                'type' => $request->type,
                'sender_user_apps_id' => auth()->user()->id,
                'recipient_user_apps_id' => $request->recipient_user_apps_id,
                'message' => $request->message,
            ]);

            $data = CommentResource::make($comment);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $comment = Comment::where('id', $request->id)->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function allComment(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 5;

        try {
            $category = 'blocx';
            $type = 'article';
            $uid = auth()->user()->id;

            if (isset($request->category)) {
                $category = $request->category;
            }

            if (isset($request->type)) {
                $type = $request->type;
            }

            $comment = Comment::query()->with('user')->withCount([
                'likeComment' => function($q) use($category, $type) {
                    $q
                    ->where('likes.type', '=', $type.'_comment')->where('likes.category', '=', $category);
                },
                'isLiked' => function($q) use($category, $type, $uid) {
                    $q
                    ->where('likes.type', '=', $type.'_comment')->where('likes.category', '=', $category)->where('likes.user_apps_id', $uid);
                },
                'anotherComment' => function($q) use($category, $type) {
                    $q
                    ->where('comments.type', '=', $type.'_comment')->where('comments.category', '=', $category);
                },
            ]);

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $comment = $comment->orderBy('id', $sort);
            }else {
                $comment = $comment->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['parent_id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        $comment = $comment->where($field, 'ILIKE', '%' . $request->q . '%');
                    }
                }
            }

            if (isset($request->category)) {
                $comment = $comment->where('category', $request->category);
            }

            if (isset($request->type)) {
                $comment = $comment->where('type', $request->type);
            }

            if (isset($request->parent_id)) {
                $comment = $comment->where('parent_id', $request->parent_id);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $comment = $comment->paginate($perPage)->appends($request->query());

            if (count($comment) < 1) {
                $msg = 'Comment Not Found';
            }


            // $data = $comment;
            $data = CommentResource::collection($comment);

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
}
