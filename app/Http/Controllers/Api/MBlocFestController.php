<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Festival;
use App\Models\FestivalEvent;
use App\Models\FestivalSchedule;
use App\Models\FestivalContent;
use App\Models\FestivalContentLike;
use App\Models\FestivalContentComment;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Gallery;
use App\Models\Notification;
use App\Http\Resources\LocationResource;
use App\Http\Resources\FestivalScheduleResource;
use App\Http\Resources\ScheduleResource;

class MBlocFestController extends Controller
{
    public function home(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        $art = 1; $music = 2; $literatur = 3;

        try {
            $festival = Festival::where([ ['status', '=', 1] ])->get();
            $data['menu'] = $festival;
            $data['seni_dan_desain'] = [];
            $data['music'] = [];
            $data['literatur'] = [];

            $festivalContentArtDesain = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $art] ])->skip(0)->take(4)->get();
            foreach ($festivalContentArtDesain as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                $data['seni_dan_desain'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'festival_content_category_id' => $value->festival_content_category_id,
                    'file' => $value->file,
                    'thumbail' => $value->thumbnail,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }

            $festivalContentMusic = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $music] ])->skip(0)->take(8)->get();
            foreach ($festivalContentMusic as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                $data['music'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'file' => $value->file,
                    'thumbail' => $value->thumbnail,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }

            $festivalContentLiteratur = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $literatur] ])->skip(0)->take(4)->get();
            foreach ($festivalContentLiteratur as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                $data['literatur'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'file' => $value->file,
                    'thumbail' => $value->thumbnail,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function find(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 10;
        $offset = 0;

        try {

            if (isset($post['limit']) && $post['limit'] != '') {
                $limit = $post['limit'];
            }
            if (isset($post['offset']) && $post['offset'] != '') {
                $offset = $post['offset'];
            }

            $festivalContent = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $post['id']] ])->orderByDesc('id')->skip($offset)->take($limit)->get();
            foreach ($festivalContent as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                if ($value->festival_id == 1) {
                    if ($value->festival_category_id == 1) {
                        $data['desain_grafis'][] = [
                            'id' => $value->id,
                            'nama' => $value->name,
                            'star' => $value->start,
                            'file' => $value->file,
                            'thumbnail' => $value->thumbnail,
                            'created_at' => $value->created_at,
                            'quotes' => $value->quotes,
                            'description' => $value->description,
                            'festival_content_category_id' => $value->festival_content_category_id,
                            'festival_content_category_name' => $value->festival_content_category_name,
                            'genre_id' => $value->genre_id,
                            'genre_name' => $value->genre_name,
                            'like'  => $festivalContentLike,
                            'comment' => $festivalContentComment,
                        ];
                    }elseif ($value->festival_category_id == 2) {
                        $data['desain_interior'][] = [
                            'id' => $value->id,
                            'nama' => $value->name,
                            'star' => $value->start,
                            'file' => $value->file,
                            'thumbnail' => $value->thumbnail,
                            'created_at' => $value->created_at,
                            'quotes' => $value->quotes,
                            'description' => $value->description,
                            'festival_content_category_id' => $value->festival_content_category_id,
                            'festival_content_category_name' => $value->festival_content_category_name,
                            'genre_id' => $value->genre_id,
                            'genre_name' => $value->genre_name,
                            'like'  => $festivalContentLike,
                            'comment' => $festivalContentComment,
                        ];
                    }
                }else {
                    $data[] = [
                        'id' => $value->id,
                        'nama' => $value->name,
                        'star' => $value->start,
                        'file' => $value->file,
                        'thumbnail' => $value->thumbnail,
                        'created_at' => $value->created_at,
                        'quotes' => $value->quotes,
                        'description' => $value->description,
                        'festival_content_category_id' => $value->festival_content_category_id,
                        'festival_content_category_name' => $value->festival_content_category_name,
                        'genre_id' => $value->genre_id,
                        'genre_name' => $value->genre_name,
                        'like'  => $festivalContentLike,
                        'comment' => $festivalContentComment,
                    ];
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function detail(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        try {
            $festivalContent = FestivalContent::where([ ['status', '=', 1], ['id', '=', $post['id']] ])->first();
            $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $post['id']] ])->count();
            $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $post['id']] ])->count();

            if ($festivalContent) {
                $totalKarya = FestivalContent::where([ ['created_by', '=', $festivalContent->created_by] ])->count();

                $data = [
                    'id' => $festivalContent->id,
                    'nama' => $festivalContent->name,
                    'star' => $festivalContent->star,
                    'quotes' => $festivalContent->quotes,
                    'description' => $festivalContent->description,
                    'thumbnail' => $festivalContent->thumbnail,
                    'file' => $festivalContent->file,
                    'created_at' => $festivalContent->created_at,
                    'created_by' => $festivalContent->created_by,
                    'created_by_name' => $festivalContent->created_by_name,
                    'total_karya' => $totalKarya,
                    'festival_content_category_id' => $festivalContent->festival_content_category_id,
                    'festival_content_category_name' => $festivalContent->festival_content_category_name,
                    'genre_id' => $festivalContent->genre_id,
                    'genre_name' => $festivalContent->genre_name,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function getComment(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 10;
        $offset = 0;

        try {

            if (isset($post['limit']) && $post['limit'] != '') {
                $limit = $post['limit'];
            }
            if (isset($post['offset']) && $post['offset'] != '') {
                $offset = $post['offset'];
            }

            $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $post['id']] ])->orderByDesc('id')->skip($offset)->take($limit)->get();
            foreach ($festivalContentComment as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'name' => $value->user_apps_name,
                    'comment' => $value->comment,
                    'created_at' => $value->created_at,
                    'label_created_at' => ''
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function getSimilar(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 10;
        $offset = 0;

        try {

            if (isset($post['limit']) && $post['limit'] != '') {
                $limit = $post['limit'];
            }
            if (isset($post['offset']) && $post['offset'] != '') {
                $offset = $post['offset'];
            }

            $festivalContent = FestivalContent::where([ ['status', '=', 1], ['festival_content_category_id', '=', $post['festival_content_category_id']], ['id', '<>', $post['id']] ])->orderByDesc('id')->skip($offset)->take($limit)->get();
            foreach ($festivalContent as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();

                $data[] = [
                    'id' => $value->id,
                    'nama' => $value->name,
                    'star' => $value->star,
                    'quotes' => $value->quotes,
                    'description' => $value->description,
                    'thumbnail' => $value->thumbnail,
                    'file' => $value->file,
                    'created_at' => $value->created_at,
                    'festival_content_category_id' => $value->festival_content_category_id,
                    'festival_content_category_name' => $value->festival_content_category_name,
                    'genre_id' => $value->genre_id,
                    'genre_name' => $value->genre_name,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function getOtherWork(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 10;
        $offset = 0;

        try {

            if (isset($post['limit']) && $post['limit'] != '') {
                $limit = $post['limit'];
            }
            if (isset($post['offset']) && $post['offset'] != '') {
                $offset = $post['offset'];
            }

            $festivalContent = FestivalContent::where([ ['status', '=', 1], ['created_by', '=', $post['created_by']], ['id', '<>', $post['id']] ])->orderByDesc('id')->skip($offset)->take($limit)->get();
            foreach ($festivalContent as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();

                $data = [
                    'id' => $value->id,
                    'nama' => $value->name,
                    'star' => $value->star,
                    'quotes' => $value->quotes,
                    'description' => $value->description,
                    'thumbnail' => $value->thumbnail,
                    'file' => $value->file,
                    'created_at' => $value->created_at,
                    'festival_content_category_id' => $value->festival_content_category_id,
                    'festival_content_category_name' => $value->festival_content_category_name,
                    'genre_id' => $value->genre_id,
                    'genre_name' => $value->genre_name,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function like(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        try {
            $festival_content_like =  FestivalContentLike::where([ ['user_apps_id', '=', auth()->user()->id], ['festival_content_id', '=', $post['id']] ])->first();
            if ($festival_content_like) {
                $festival_content_like->delete();
            }else {
                $festival_content_like = FestivalContentLike::create([
                    'user_apps_id'        => auth()->user()->id,
                    'festival_content_id' => $post['id'],
                ]);
            }
            $data = $festival_content_like;
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function comment(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        try {
            $festival_content_like = FestivalContentComment::create([
                'user_apps_id'        => auth()->user()->id,
                'festival_content_id' => $post['id'],
                'comment'             => $post['comment'],
            ]);
            $data = $festival_content_like;
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function deleteComment(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        try {
            $festival_content_comment = FestivalContentComment::destroy($post['id']);
            $data = $festival_content_comment;
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function myCreationMenu(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $art = 1; $music = 2; $literatur = 3;
        // $id = auth()->user()->id;
        $id = 1;
    }

    public function myCreation(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $art = 1; $music = 2; $literatur = 3;
        // $id = auth()->user()->id;
        $id = 1;

        try {
            $myCreation = FestivalContent::where([ ['id', '=', $id], ['created_by', '=', $id] ])->first();
            $data['name'] = $myCreation->name;
            $data['total'] = FestivalContent::where([ ['id', '=', $id], ['created_by', '=', $id] ])->count();
            $data['seni_dan_desain'] = [];
            $data['music'] = [];
            $data['literatur'] = [];


            $festivalContentArtDesain = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $art], ['created_by', '=', $id] ])->get();
            foreach ($festivalContentArtDesain as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                $data['seni_dan_desain'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'festival_content_category_id' => $value->festival_content_category_id,
                    'festival_content_category_name' => $value->festival_content_category_name,
                    'file' => $value->file,
                    'thumbail' => $value->thumbnail,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }

            $festivalContentMusic = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $music], ['created_by', '=', $id] ])->get();
            foreach ($festivalContentMusic as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                $data['music'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'file' => $value->file,
                    'thumbail' => $value->thumbnail,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }

            $festivalContentLiteratur = FestivalContent::where([ ['status', '=', 1], ['festival_id', '=', $literatur], ['created_by', '=', $id] ])->get();
            foreach ($festivalContentLiteratur as $key => $value) {
                $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $value->id] ])->count();
                $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $value->id] ])->count();
                $data['literatur'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'file' => $value->file,
                    'thumbail' => $value->thumbnail,
                    'like' => $festivalContentLike,
                    'comment' => $festivalContentComment,
                ];
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function homeV2(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        $art = 1; $music = 2; $literatur = 3;

        try {
            $festival = Festival::get();

            foreach ($festival as $key => $val) {
                $data[] = [
                    'id' => $val->id,
                    'name' => $val->name,
                    'menu_name' => $val->menu_name,
                    'navigation' => $val->name,
                    'file' => asset('img/' . $val->file),
                    'status' => $val->status
                ];
            }

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

    public function findV2(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 10;
        $offset = 0;

        try {

            if (isset($post['limit']) && $post['limit'] != '') {
                $limit = $post['limit'];
            }
            if (isset($post['offset']) && $post['offset'] != '') {
                $offset = $post['offset'];
            }

            $festivalContent = FestivalEvent::where([ ['status', '=', 1], ['festival_id', '=', $post['id']] ])->orderByDesc('id')->skip($offset)->take($limit)->get();
            foreach ($festivalContent as $key => $value) {
                $data[] = [
                    'event_id' => $value->id,
                    'nama' => $value->name,
                    'file' => asset('img/' . $value->file),
                    'thumbnail' => asset('img/' . $value->thumbnail),
                    'created_at' => $value->created_at,
                    'quotes' => $value->quotes,
                    'description' => $value->description,
                ];
            }

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

    public function detailV2(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        try {
            // $festivalEvent = FestivalEvent::where([ ['status', '=', 1], ['id', '=', $post['event_id']] ])->first();
            $festivalEvent = FestivalEvent::selectRaw('festival_events.*, locations.name as location_name')
            ->join('locations', 'festival_events.location_id', '=', 'locations.id')
            ->where('festival_events.status', '=', 1)->where('festival_events.id', '=', $post['event_id'])->first();
            // $festivalContentLike = FestivalContentLike::where([ ['festival_content_id', '=', $post['id']] ])->count();
            // $festivalContentComment = FestivalContentComment::where([ ['festival_content_id', '=', $post['id']] ])->count();

            if ($festivalEvent) {
                // $totalKarya = FestivalContent::where([ ['created_by', '=', $festivalEvent->created_by] ])->count();

                $data = [
                    'event_id' => $festivalEvent->id,
                    'nama' => $festivalEvent->name,
                    'description' => $festivalEvent->description,
                    'file' => asset('img/' . $festivalEvent->file),
                    'thumbail' => asset('img/' . $festivalEvent->thumbnail),
                    'created_at' => $festivalEvent->created_at,
                    'created_by' => $festivalEvent->created_by,
                    'created_by_name' => $festivalEvent->created_by_name,
                    'date' => date('d M', strtotime($festivalEvent->festival_event_date_start)).' - '.date('d M Y', strtotime($festivalEvent->festival_event_date_end)),
                    'time' => $festivalEvent->festival_event_time_start.' - '.$festivalEvent->festival_event_time_end,
                    'location_id' => $festivalEvent->location_id,
                    'location_name' => $festivalEvent->location_name,
                ];
            }
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

    public function todaysLineUp(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 2;
        $offset = 0;
        $music = 1;

        try {
            if (isset($post['limit'])) {
                $limit = $post['limit'];
            }

            if (isset($post['offset'])) {
                $offset = $post['offset'];
            }

            $festivalSchedule = FestivalSchedule::selectRaw('festival_schedules.*, locations.name as location_name')
            ->leftJoin('locations', 'locations.id', '=', 'festival_schedules.location_id');
            if (!empty($post['event_id'])) {
                $festivalSchedule = $festivalSchedule->where('festival_schedules.event_id', '=', $post['event_id']);
            }
            $festivalSchedule = $festivalSchedule->where('status', '=', 1)->whereDate('festival_schedules.festival_schedule_date_end', '>=', date('Y-m-d'))->skip($offset)->take($limit)->get();

            foreach ($festivalSchedule as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'star' => $value->star,
                    'time' => $value->festival_schedule_time_start.' - '.$value->festival_schedule_time_end,
                    'date' => date('d M Y', strtotime($value->festival_schedule_date_start)),
                    'festival_content_category_id' => $value->festival_content_category_id,
                    'file' => asset('img/' . $value->file),
                    'thumbail' => asset('img/' . $value->thumbnail),
                    'location_id' => $value->location_id,
                    'location_name' => $value->location_name
                ];
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function eventBerlangsung(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 2;
        $offset = 0;

        try {
            if (isset($post['limit'])) {
                $limit = $post['limit'];
            }

            if (isset($post['offset'])) {
                $offset = $post['offset'];
            }

            $festivalEvent = FestivalEvent::where('festival_events.status', '=', 1)->join('festivals', 'festivals.id', '=', 'festival_events.festival_id')
            ->selectRaw('festival_events.*, festivals.name as menu_name');
            if (isset($post['upp_comming']) && $post['up_comming'] == 1) {
                $festivalEvent  = $festivalEvent->whereDate('festival_events.festival_event_date_start', '>=', date('Y-m-d'))->whereDate('festival_events.festival_event_date_end', '>=', date('Y-m-d'));
            }else {
                $festivalEvent  = $festivalEvent->whereDate('festival_events.festival_event_date_start', '<=', date('Y-m-d'))->whereDate('festival_events.festival_event_date_end', '>=', date('Y-m-d'));
            }
            $festivalEvent = $festivalEvent->orderBy('festival_events.id')->skip(0)->take(2)->get();

            foreach ($festivalEvent as $key => $value) {
                $data[$value['menu_name']][] = [
                    'event_id' => $value->id,
                    'name' => $value->name,
                    'description' => $value->description,
                    'date' => $value->festival_event_date_start.' - '.$value->festival_event_date_end,
                    'time' => $value->festival_event_time_start.' - '.$value->festival_event_time_end,
                    'file' => asset('img/' . $value->file),
                    'thumbail' => asset('img/' . $value->thumbnail),
                    'category_id' => $value->category_id,
                    'category_name' => $value->category_name,
                    'navigation' => $value['menu_name']
                ];
            }
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

    public function eventDetail(Request $post){
        $success = true;
        $data = [];
        $msg = null;

        try {
            $festivalEvent = FestivalEvent::selectRaw('festival_events.*, locations.name as location_name')
            ->join('locations', 'festival_events.location', '=', 'locations.id')
            ->where('status', '=', 1)->where('id', '=', $post['event_id'])->first();
            $data[] = [
                'event_id' => $festivalEvent->id,
                'name' => $festivalEvent->name,
                'description' => $festivalEvent->description,
                'date' => date('d M', strtotime($festivalEvent->festival_event_date_start)).' - '.date('d M Y', strtotime($festivalEvent->festival_event_date_end)),
                'time' => $festivalEvent->festival_event_time_start.' - '.$festivalEvent->festival_event_time_end,
                'file' => asset('img/' . $festivalEvent->file),
                'thumbail' => asset('img/' . $festivalEvent->thumbnail),
                'category_id' => $festivalEvent->category_id,
                'category_name' => $festivalEvent->category_name,
                'location_id' => $festivalEvent->location_id,
                'location_name' => $festivalEvent->location_name,
            ];
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

    public function eventSchedule(Request $request){
        $success = true;
        $data = [];
        $schedule = [];
        $msg = null;
        $perPage = 4;

        try {
            $festivalSchedule = FestivalSchedule::where([ ['status', '=', 1] ])->orderBy('festival_schedule_date_start')->with('location', 'schedule');

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $festivalSchedule = $festivalSchedule->paginate($perPage);
            // $data = $festivalSchedule;
            $data = FestivalScheduleResource::collection($festivalSchedule);
            $schedule = ScheduleResource::collection($festivalSchedule);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'date'    => $schedule,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    public function location(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $perPage = 4;

        try {

            if(isset($post['category']) && $post['category'] != 3){
                $category = $post['category'];
            }

            if ($post['type'] == 'venues') {
                $location = Location::with(['galleries_venues']);
            }elseif ($post['type'] == 'experience') {
                $location = Location::with(['galleries_experience']);
            }

            if (isset($post['type'])) {
                $location = $location->where('type', $post['type']);
            }
            if(isset($post['category']) && $post['category'] != 3){
                $location = $location->where('entrance', $category);
            }

            if ($post['perPage']) {
                $perPage = $post->perPage;
            }

            $location = $location->paginate($perPage);

            $data = LocationResource::collection($location);

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

    public function galleries(Request $post){
        $success = true;
        $data = [];
        $msg = null;
        $limit = 3;
        $offset = 0;

        try {
            if (isset($post['limit'])) {
                $limit = $post['limit'];
            }

            if (isset($post['offset'])) {
                $offset = $post['offset'];
            }

            $galleries = Gallery::where([ ['type', '=', 'event'], ['parent_id', '=', $post['event_id']] ])->get();
            foreach ($galleries as $key => $val) {
                $data[] = [
                    'image'  => asset('img/' . $val->image),
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function itung(Request $request){
        $success = true;
        $data = null;
        $msg = null;
        $uaid = auth()->user()->id;

        try {
            $notif = Notification::where([ ['user_apps_id', '=', $uaid], ['status', '=', 0] ])->count();

            $data = $notif;
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }

}
