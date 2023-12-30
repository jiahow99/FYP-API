<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestMechanicRequest extends FormRequest
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
            'userId' => 'string|required',
            'placeId' => 'string|required',
            'userToken' => 'string|required',
            'problems' => 'string|required',
            'yearMake' => 'numeric',
            'brand' => 'string|required',
            'model' => 'string|required',
            'mileage' => 'string|required',
            'name' => 'string|required',
            'phone' => 'string|required',
            'remark' => 'string',
            'images' => 'array',
            'userLatitude' => 'numeric', 
            'userLongitude' => 'numeric', 
        ];
    }
}
