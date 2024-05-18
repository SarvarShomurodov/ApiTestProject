<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
        if(request()->isMethod('post')) {
            return [
            'title' => 'required|string|max:255',
            'short_description' => 'required',
            'long_description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ];
        } else {
            return [
                     'title' => 'required|string|max:255',
            'short_description' => 'required',
            'long_description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ];
        }
    }

        // return [
        //     'title' => 'required|string|max:255',
        //     'short_description' => 'required',
        //     'long_description' => 'required',
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        // ];

}
