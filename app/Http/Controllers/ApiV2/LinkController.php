<?php

namespace App\Http\Controllers\ApiV2 ;

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
        $description = '';
        $title = '';
        $link_image = 'https://storage.googleapis.com/tribes-prod/images/banner/tribes-banner-2.png';
        $response = $query[0]->custom_scheme_url;
        
        return view("redirectScheme", [
            'uri' => $response,
            'description' => $description,
            'title' => $title,
            'link_image' => $link_image,
            'ios_link' => "https://apps.apple.com/id/app/evoria/id6443814266",
            'android_link' => "https://play.google.com/store/apps/details?id=com.evoriaappid",
        ]);
    }
}
