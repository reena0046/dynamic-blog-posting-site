{{-- Auth modals: appear over the current frontend page --}}
<div class="auth-modal-overlay" id="authModalOverlay" hidden aria-hidden="true">
    <div class="auth-modal" role="dialog" aria-modal="true" aria-label="Authentication">
        <button type="button" class="auth-modal-close" id="authModalClose" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="auth-modal-panel" data-auth-panel="login" hidden>
            <h2 class="auth-title" id="authModalTitleLogin">Welcome back!</h2>
            <p class="auth-sub">Sign in to continue to BlogSpace</p>

            @include('Frontend.partials.google-button')

            <p class="auth-switch">
                Don't have an account?
                <a href="{{ route('register') }}" class="js-auth-switch" data-auth-target="register">Register</a>
            </p>
        </div>

        <div class="auth-modal-panel" data-auth-panel="register" hidden>
            <h2 class="auth-title" id="authModalTitleRegister">Create your account</h2>
            <p class="auth-sub">Join BlogSpace and start exploring amazing blogs.</p>

            @include('Frontend.partials.google-button')

            <p class="auth-switch">
                Already have an account?
                <a href="{{ route('login') }}" class="js-auth-switch" data-auth-target="login">Log in</a>
            </p>
        </div>
    </div>
</div>
