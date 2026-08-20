@if (session('success'))
    <div class="flash-message flash-success">
        <i class="bi bi-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="flash-message flash-error">
        <i class="bi bi-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif
