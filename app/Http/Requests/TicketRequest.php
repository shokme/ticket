<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
        $rules = [
            'title' => ['required', 'string'],
            'content' => ['required', 'min:6'],
        ];

        if(in_array($this->method(), ['PATCH', 'PUT'])) {
            $rules = [
                'content' => ['required', 'min:6'],
            ];
        }

        return $rules;
    }
}
