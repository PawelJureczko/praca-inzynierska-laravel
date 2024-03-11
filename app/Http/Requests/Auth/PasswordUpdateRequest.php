<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.current_password' => 'Hasło jest nieprawidłowe.',
            'current_password.required' => 'Wpisz obecne hasło.',
            'password.required' => 'Wpisz nowe hasło.',
            'password.confirmed' => 'Hasła nie są jednakowe.',
            'password.min' => 'Hasło musi mieć przynajmniej :min znaków.',
            'password.mixed_case' => 'The new password must contain both uppercase and lowercase letters.',
            'password.letters' => 'The new password must contain letters.',
            'password.numbers' => 'The new password must contain numbers.',
            'password.symbols' => 'The new password must contain symbols.',
            'password.uncompromised' => 'The new password has been compromised and cannot be used.',
        ];
    }
}
