<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login_field' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginField = $this->input('login_field');
        $password = $this->input('password');

        // Determine if login field is email or NPM
        $isEmail = filter_var($loginField, FILTER_VALIDATE_EMAIL);

        $credentials = [
            'password' => $password
        ];

        if ($isEmail) {
            // Login with email (for admin)
            $credentials['email'] = $loginField;
        } else {
            // Login with NPM (for alumni)
            $credentials['nim'] = $loginField;
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login_field' => trans('auth.failed'),
            ]);
        }

        // Check user status after successful authentication
        $user = Auth::user();

        if ($user->status === 'pending') {
            Auth::logout();
            RateLimiter::clear($this->throttleKey());

            throw ValidationException::withMessages([
                'login_field' => 'Akun Anda masih menunggu persetujuan admin. Silakan coba lagi nanti.',
            ]);
        }

        if ($user->status === 'rejected') {
            Auth::logout();
            RateLimiter::clear($this->throttleKey());

            throw ValidationException::withMessages([
                'login_field' => 'Pendaftaran Anda telah ditolak. Silakan hubungi administrator untuk informasi lebih lanjut.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_field' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login_field')).'|'.$this->ip());
    }
}
