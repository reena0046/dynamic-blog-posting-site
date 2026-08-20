<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('Frontend.Pages.login');
    }

    public function register(): View
    {
        return view('Frontend.Pages.register');
    }
}
