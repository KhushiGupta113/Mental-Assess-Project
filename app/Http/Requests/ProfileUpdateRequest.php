<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'age_group' => ['nullable', 'string', 'in:under_18,18-24,25-34,35-44,45-54,55_plus'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'lifestyle_habits' => ['nullable', 'string', 'max:1000'],
            'sleep_tracking' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'avatar' => ['nullable', 'string', 'max:10'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_anonymous' => $this->has('is_anonymous'),
        ]);
    }
}
