<?php

namespace App\Http\Requests\API\Coordinator;

use App\Http\Requests\API\FormRequest;
use App\Models\Sector;
use App\Rules\Unique;

class UpdateSector extends FormRequest
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
        $sector = Sector::findOrFail($this->route('id'));

        return [
            'name' => ['required', 'max:50', new Unique('sectors', 'name', $sector->id)],
            'description' => ['nullable', 'max:8000'],
            'active' => ['required', 'boolean'],
        ];
    }
}
