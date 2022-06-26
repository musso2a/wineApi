<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
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
            'year' => 'required|numeric',
            'price' => 'required|numeric',
            'condition' => 'required|numeric',
            'description' => 'required|max:255|string',
            'images' => 'nullable',
            'color' => 'required',
            'trade' => 'required|boolean',
            'provenance' => 'required|max:255|string',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
