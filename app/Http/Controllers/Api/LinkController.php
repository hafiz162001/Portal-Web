<?php

namespace App\Http\Controllers\Api ;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\LinkRedirect;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller
{

    public function getCustomSchemaUrl($code) {
        $query = LinkRedirect::where('code', $code)->get();
        $link = $query[0];
        if($link->ref_type == 'picu_wujudkan'){
            $articles = Article::findOrFail($link->ref_id);
        }
        $description = '';
        $title = '';
        $link_image = 'https://storage.googleapis.com/tribes-prod/images/banner/tribes-banner-2.png';
        // if( strlen($getProduct->image) > 0) {
            //     $link_image = $getProduct->image;
        // }
        $response = $query[0]->custom_scheme_url;
        
        return view("redirectScheme", [
            'uri' => $response,
            'description' => $description,
            'title' => $title,
            'link_image' => $link_image,
            'ios_link' => "https://apps.apple.com/id/app/blocx-id/id1642441614",
            'android_link' => "https://play.google.com/store/apps/details?id=com.mblocxperienceapp",
        ]);
    }
}
