<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        return redirect()
            ->to($this->authReturnUrl($request))
            ->with('open_auth_modal', 'login');
    }

    public function register(Request $request): RedirectResponse
    {
        return redirect()
            ->to($this->authReturnUrl($request))
            ->with('open_auth_modal', 'register');
    }

    private function authReturnUrl(Request $request): string
    {
        $previous = url()->previous();
        $authUrls = [route('login'), route('register')];

        if (! $previous || in_array($previous, $authUrls, true) || $previous === $request->url()) {
            return route('home');
        }

        return $previous;
    }
}
