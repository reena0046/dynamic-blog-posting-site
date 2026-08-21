<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | BlogSpace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/admin/css/admin.css">

</head>

<body>
    <div class="login-page">
        <div class="login-card">
            <h1 class="login-logo">Blog<span>Space</span></h1>
            <p class="login-sub">Sign in to the admin panel</p>

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <label class="form-label-admin" for="email">Email</label>
                <input type="email" id="email" name="email"
                    class="form-input login-input @error('email') is-invalid @enderror" value="{{ old('email') }}"
                    placeholder="Enter Email" autocomplete="username" required>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                <label class="form-label-admin" for="password">Password</label>
                <div class="password-field">
                    <input type="password" id="password" name="password"
                        class="form-input login-input @error('password') is-invalid @enderror"
                        placeholder="Enter password" autocomplete="current-password" required>
                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                <button type="submit" class="login-btn">Sign In</button>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function() {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            this.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            this.querySelector('i').className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>
</body>

</html>
