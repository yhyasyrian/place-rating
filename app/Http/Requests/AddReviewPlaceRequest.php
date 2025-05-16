<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddReviewPlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string|min:3',
            'service_rating' => 'integer|min:1|max:5',
            'quality_rating' => 'integer|min:1|max:5',
            'cleanliness_rating' => 'integer|min:1|max:5',
            'price_rating' => 'integer|min:1|max:5',
        ];
    }
}
