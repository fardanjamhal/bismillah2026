<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'title' => 'required|string|max:191',
            'thumbnail' => request()->isMethod('PUT') ? 'sometimes' : 'required',
            'sort_description' => 'required|string|max:500',
            'description' => 'required',
            'is_published' => 'required|in:0,1',
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('news.title'),
            'thumbnail' => __('news.thumbnail'),
            'sort_description' => __('news.sort_description'),
            'description' => __('news.description'),
            'is_published' => __('news.is_published'),
        ];
    }
}
