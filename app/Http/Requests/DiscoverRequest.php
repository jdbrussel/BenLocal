<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscoverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region' => ['sometimes', 'string'],
            'area' => ['sometimes', 'string'],
            'place' => ['sometimes', 'string'],
            'sector' => ['sometimes', 'string'],
            'category' => ['sometimes', 'string'],
            'latitude' => ['sometimes', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'numeric', 'between:-180,180'],
            'radius' => ['sometimes', 'numeric', 'min:1', 'max:500'],
            'q' => ['sometimes', 'nullable', 'string', 'min:2'],
            'sort' => ['sometimes', 'string', 'in:relevant,newest,most_recommended,highest_rated,nearest,hidden_gems'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
