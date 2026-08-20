@extends('layouts.frontend')

@section('title', 'Log in | BlogSpace')

@section('body_class', 'auth-layout')

@section('content')
    <section class="auth-page">
        <div class="auth-backdrop" aria-hidden="true"></div>
        <div class="container">
            <div class="auth-card">
                <h1 class="auth-title">Welcome back!</h1>
                <p class="auth-sub">Sign in to continue to BlogSpace</p>

                @include('Frontend.partials.google-button')

                <p class="auth-switch">
                    Don't have an account?
                    <a href="{{ route('register') }}">Register</a>
                </p>
            </div>
        </div>
    </section>
@endsection
