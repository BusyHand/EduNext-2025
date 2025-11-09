<?php

namespace Modules\Auth\Http\Requests;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserRegisterRequest extends Data
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
        public readonly string $passwordConfirmation,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:500',],
            'passwordConfirmation' => ['required', 'string', 'min:8', 'max:500','same:password'],
        ];
    }

    public static function messages(): array
    {
        return [
            'username.required' => 'Имя пользователя обязательно для заполнения',
            'username.unique' => 'Это имя пользователя уже занято',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.unique' => 'Этот email уже зарегистрирован',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'passwordConfirmation.same' => 'Пароли не совпадают',
            'passwordConfirmation.required' => 'Подтверждение пароля обязательно',
        ];
    }
}