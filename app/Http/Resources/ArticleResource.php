<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ArticleDetailResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $data[$this->category_name] = [
        //     'id' => $this->id,
        //     'title' => $this->title,
        //     'subtitle' => $this->subtitle,
        //     'publisher' => $this->publisher,
        //     'created_at' => $this->created_at?->format('d M Y'),
        //     'category_id' => $this->category_id,
        //     'category_name' => $this->category_name,
        // ];
        // return $data;
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'publisher'     => $this->publisher,
            'descriptions'  => $this->descriptions,
            'created_at'    => $this->created_at?->format('d M Y'),
            'category_id'   => $this->category_id,
            'category_name' => $this->category_name,
            'liked_count'   => $this->liked_count,
            'comment_count' => $this->comment_count,
            'views_count'   => $this->views_count,
            'is_liked'      => $this->is_liked_count == 1 ? true : false,
            'images'        => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'detail'        => empty($this->article_details) ? null : ArticleDetailResource::collection($this->article_details),
            'share_link' => $this->linkRedirect?->code ? 'http://api.blocx.id/api/l/'.$this->linkRedirect->code : null,
            // 'price' => $this->price,
            // 'description' => $this->description,
            // 'category' => $this->productCategory->name,
        ];

    }
}
