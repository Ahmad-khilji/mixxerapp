<?php

namespace App\Http\Requests\Api\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|exists:users,email|email',
            'password' => 'required',
        ];
    }
    
    public function messages()
    {
        return [
            'email.required' => 'Please enter the email address',
            'email.exists' => 'This email adress is not registered',
            'password.required' => 'Please enter the password',
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
