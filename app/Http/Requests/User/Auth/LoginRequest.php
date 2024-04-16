<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Models\User;

class LoginRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:' . User::class,
            'password' => 'required',
        ];
    }
}
