<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class RegisterRequest extends FormRequest

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
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Please enter the Email Address',
            'email.unique' => 'Email Address is already registered',
            'email.email' => 'Please enter a valid Email Address',
            'password.required' => 'Please enter the Password',
            'first_name.required' => 'Please enter the First Name',
            'last_name.required' => 'Please enter the Last Name',
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
