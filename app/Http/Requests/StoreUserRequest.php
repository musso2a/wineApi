<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|string',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
            'avatar' => 'nullable|file',
            'is_major' => 'required|boolean',
            'note' => 'nullable|numeric',
            'subscription' => 'nullable|numeric',
            'favorite_wine_id' => 'nullable|exists:wines,id',
        ];
    }
}
