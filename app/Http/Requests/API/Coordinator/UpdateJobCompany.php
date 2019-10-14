<?php

namespace App\Http\Requests\API\Coordinator;

use App\Http\Requests\API\FormRequest;
use App\Rules\CNPJ;
use App\Rules\CPF;
use App\Rules\Integer;

class UpdateJobCompany extends FormRequest
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
            'ie' => ['nullable', new Integer, 'digits:10'],
            'name' => ['required', 'max:191'],
            'fantasyName' => ['nullable', 'max:191'],

            'representativeName' => ['required', 'max:50'],
            'representativeRole' => ['required', 'max:50'],

            'active' => ['required', 'boolean'],
        ];
    }
}
