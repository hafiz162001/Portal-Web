<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\ChannelArticle;
use App\Models\Tag;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class FrontController extends Controller

{
    public function index(Article $article) {
        $banner = Banner::latest()->limit(1)->get();
        $channels = ChannelArticle::orderBy('id', 'asc')->get();
        $channel9 = ChannelArticle::where('parent_id', NULL)->orderBy('id', 'asc')->limit(9)->get();
        $categories = Article::where('category_name', 'Top 10 Berita')->limit('10')->get();
        $channel = Article::all();
        $articles = Article::latest()->Filter(Request(['search','category', 'channel']))->limit(4)->get();
        if (request('search')) {
            // $articles = Article::latest()->get();
            // $articles->where('title', 'like', '%' . request('search') . '%')
            // ->where('descriptions', 'like', '%' . request('search') . '%');
            $channel = Article::latest()->Filter(Request(['search']))->paginate(10)->withQueryString();
            return view('front.channels', compact('channel','channels','channel9'));
        }
        $article10 = Article::orderBy('id', 'desc')->limit(7)->get();
        $BeritaUtama = Article::where('category_name', 'Berita Utama')->limit(4)->get();
        $BeritaTerkini = Article::where('category_name', 'Berita Terkini')->limit(9)->get();
        $cardArticle9 = Article::limit(9)->get();
        $cardArticle8 = Article::limit(8)->get();
        $cardArticle4 = Article::limit(4)->get();
        $cardArticle10 = Article::limit(10)->get();
        $tags = Tag::all();
        return view('front.index', compact('banner','channels','tags','channel9', 'categories','article','articles','article10','BeritaUtama','BeritaTerkini','cardArticle9','cardArticle8','cardArticle4','cardArticle10'));
    }

    public function detail($slug) {
        $baca_juga = Article::all();
        $rndIndex = rand(0, $baca_juga->count() - 1);
        $article = Article::where('slug', $slug)->first();
        $channels = ChannelArticle::orderBy('id', 'asc')->get();
        $channel9 = ChannelArticle::where('parent_id', NULL)->orderBy('id', 'asc')->limit(9)->get();
        $description = $article->descriptions;
        $paragraphs = preg_split("/\R{2,}/", $description); // Pisahkan paragraf
        $cardArticle10 = Article::limit(10)->get();
        $cardArticle4 = Article::limit(4)->get();
        $cardArticle8 = Article::limit(8)->get();
        $tags = Tag::all();
        return view ('front.detail',compact('article','channels', 'tags', 'paragraphs','baca_juga', 'channel9', 'cardArticle10', 'cardArticle4', 'cardArticle8', 'rndIndex'));
    }

    public function channels($canel, Request $request)
    {
        $channels = ChannelArticle::orderBy('id', 'asc')->get();
        $channel9 = ChannelArticle::where('parent_id', NULL)->orderBy('id', 'asc')->limit(9)->get();
        $categories = Article::where('category_name', 'Top 10 Berita')->limit('10')->paginate(10)->withQueryString();
        $articles = Article::latest()->Filter(Request(['search']))->get();
        $tags = Tag::all();
        $channel = Article::where('channel_name', $canel)->orWhere('category_name', $canel)->orWhere('tag_id',intval($canel))->latest()->get();
        return view('front.channels', compact('channel','articles','channels','categories','tags','channel9'));
    }

}
