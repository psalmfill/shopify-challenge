<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddImageRequest extends FormRequest
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
        if (request()->has('images') and is_array(request()->images)) {
            return   [
                'images' => ['required'],
                'caption' => ['required'],
                'folder_id' => 'nullable|exists:folders,id'
            ];
        }


        return  [
            'image' => ['required'],
            'caption' => ['required',  Rule::unique('images')->where('user_id', auth()->id())],
            'folder_id' => 'nullable|exists:folders,id'
        ];
    }
}
