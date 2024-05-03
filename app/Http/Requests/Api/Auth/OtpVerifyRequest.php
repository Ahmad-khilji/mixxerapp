<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;
class OtpVerifyRequest extends FormRequest
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
            'email' => 'required|email|exists:otp_verifies,email',
            'otp' => 'required|min:6'
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Please enter the email address',
            'email.email' => 'Please enter a valid email address',
            'email.exists' => 'The provided email does not exist ',
            'otp.required' => 'Please enter the otp code',
            'otp.min' => 'Please enter 6 digit code',
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
