<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'overall_rating' => 'required|numeric|min:1|max:5',
            'rating_values' => 'nullable|array',
            'review_text' => 'nullable|string|max:2000',
            'recommendation_id' => 'nullable|exists:recommendations,id',
            'confirms_recommendation' => 'nullable|boolean',
            'visited_at' => 'nullable|date',
            'verified_visit' => 'nullable|boolean',
        ];
    }
}
