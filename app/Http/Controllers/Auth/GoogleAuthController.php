<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            Log::warning('Google OAuth callback failed.', [
                'message' => $exception->getMessage()
            ]);

            return redirect()
                ->route('login')
                ->with('error', 'Google sign-in failed. Please try again.');
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()
                ->route('login')
                ->with('error', 'Google did not provide an email address.');
        }

        $user = User::query()
            ->where(function ($query) use ($googleUser, $email) {
                $query->where('google_id', $googleUser->getId())
                    ->orWhere('email', $email);
            })
            ->first();

        if ($user && $user->is_admin) {
            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Admin accounts must sign in with email and password.'
                ]);
        }

        if ($user) {
            $user->update([
                'name' => $googleUser->getName() ?: $user->name,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?: $user->avatar,
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'User',
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
                'is_admin' => false,
            ]);
        }

        Auth::login($user);

        request()->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'You are signed in successfully.');
    }
}
