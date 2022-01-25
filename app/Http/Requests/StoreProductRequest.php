<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'size' => 'required|string',
            'price' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'mark' => 'string|nullable',
            'image' => 'required',
            'category_id' => 'required|numeric',
        ];
    }
}
