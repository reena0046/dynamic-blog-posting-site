@php
    $avatarClass = $class ?? 'user-avatar-sm';
    $extraClass = trim((string) ($extraClass ?? ''));
    $avatarUrl = $user->avatarUrl();
    $initials = $user->initials();
@endphp

@if ($avatarUrl)
    <div class="{{ $avatarClass }} has-photo {{ $extraClass }}" data-initials="{{ $initials }}">
        <img src="{{ $avatarUrl }}"
            alt="{{ $user->name }}"
            referrerpolicy="no-referrer"
            onerror="var el=this.parentElement; el.classList.remove('has-photo'); el.textContent=el.getAttribute('data-initials') || '';">
    </div>
@else
    <div class="{{ $avatarClass }} {{ $extraClass }}">{{ $initials }}</div>
@endif
