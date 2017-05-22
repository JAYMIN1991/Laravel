<?php

namespace App\Modules\Services\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AutoSuggestRequest
 * @package App\Modules\Services\Http\Requests
 * @deprecated Autosuggest Controller itself is depricated
 */
class AutoSuggestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'source' =>'required|in:'
        ];
    }




}
