<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => ['required','string','max:255'],
            'email'         => ['required','email','max:255','unique:users,email'],
            'password'      => ['required','string','min:6','confirmed'],
            'username'      => ['nullable','string','max:255','unique:users,username'],
            'phone_number'  => ['nullable','string','max:30'],
            'role'          => ['nullable','string','max:50'],         // label opsional
            'default_role'  => ['nullable','string','max:50'],         // spatie role yang di-assign
        ];
    }
}
