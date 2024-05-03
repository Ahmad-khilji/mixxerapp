<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class NewPasswordRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Please enter the email address',
            'email.email' => 'Please enter a valid email address',
            'email.exists' => 'The email address is not registered ',
            'password.required' => 'Please enter the password',
            'password.min' => 'Please enter 6 digits in password',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorMessage = implode(', ', $validator->errors()->all());
 
        throw new HttpResponseException(response()->json([
            'status'   => false,
            'action' => $errorMessage
        ]));
    }
}
