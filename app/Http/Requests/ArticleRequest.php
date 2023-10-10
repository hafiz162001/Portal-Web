<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'title' => 'required',
            // 'slug' => 'required|unique:articles,slug',
            // 'publisher' => 'required',
            // 'tag' => 'required',
            // 'creator' => 'required',
            // 'category' => 'required',
            // 'image' => 'max:2048',
            // 'type' => 'required',
        ];
    }
}
