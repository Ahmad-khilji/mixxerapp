<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SocialRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'platform' => 'required',
            'platform_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Please enter the email address',
            'email.unique' => 'Email address is already registered',
            'email.email' => 'Please enter a valid email address',
            'platform.required' => 'Please enter the platform',
            'platform_id.required' => 'Please enter the platform_id',
            'first_name.required' => 'Please enter the first name',
            'last_name.required' => 'Please enter the last name',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errorMessage = implode(', ', $validator->errors()->all());

        throw new HttpResponseException(response()->json([
            'status'   => false,
            'message' => 'Validation Error',
            'action' => $errorMessage
        ]));
    }
}
