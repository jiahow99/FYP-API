<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptMechanicRequest extends FormRequest
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
        return [
            'status' => 'string|required',
            'trackingId' => 'string',
            'placeId' => 'string|required',
            'userId' => 'string|required',
            'userToken' => 'string|required',
            'storeToken' => 'string|required',
            'storeName' => 'string|required'
        ];
    }
}
