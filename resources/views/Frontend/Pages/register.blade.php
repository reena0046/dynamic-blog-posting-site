@extends('layouts.frontend')

@section('title', 'Sign up | BlogSpace')

@section('body_class', 'auth-layout')

@section('content')
    <section class="auth-page">
        <div class="auth-backdrop" aria-hidden="true"></div>
        <div class="container">
            <div class="auth-card">
                <h1 class="auth-title">Create your account</h1>
                <p class="auth-sub">Join BlogSpace and start exploring amazing blogs.</p>

                @include('Frontend.partials.google-button')

                <p class="auth-switch">
                    Already have an account?
                    <a href="{{ route('login') }}">Log in</a>
                </p>
            </div>
        </div>
    </section>
@endsection
