<?php

namespace App\Modules\Account\Http\Requests\commission;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'course_type' => 'required|numeric',
            'institute_id' => 'required',
            'apply_commission' => 'required|numeric',
            'not_applicable' => 'sometimes|bool',
        ];
    }
}
