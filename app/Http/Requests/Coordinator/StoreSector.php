<?php

namespace App\Http\Requests\Coordinator;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class StoreSector extends FormRequest
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
            'name' => ['required', 'max:50', new Unique('sectors', 'name')],
            'description' => ['nullable', 'max:8000'],
            'active' => ['required', 'boolean'],
        ];
    }
}
