<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Api</title>

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('/img/hris.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h4 class="text-center mb-4">Login</h4>

                <div id="errorBox" class="alert alert-danger d-none"></div>

                <form id="loginForm">

                    <div class="mb-3">
                        <label class="form-label">Username / Email / Phone</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword()">👁️</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Superadmin">Admin</option>
                            <option value="Manager HRD">Manajer HRD</option>
                            <option value="Admin HRD">Staf HRD</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Captcha</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" name="captcha" class="form-control" placeholder="Masukkan captcha"
                                required>
                            <strong class="bg-secondary text-white px-3 py-2 rounded d-block text-center">
                                <span id="captchaText" class="fw-bold"></span>
                            </strong>
                            {{-- <button type="button" class="btn btn-sm btn-secondary" onclick="loadCaptcha()">↻</button> --}}
                        </div>
                        <input type="hidden" id="captcha_token">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" value="1">
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <span id="btnLoading" class="spinner-border spinner-border-sm me-2 d-none"></span>
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            let input = document.getElementById('password');
            input.type = input.type === "password" ? "text" : "password";
        }
        document.addEventListener('DOMContentLoaded', function() {
            loadCaptcha();
        });
        // ================= CAPTCHA =================
        function loadCaptcha() {
            fetch('/api/captcha')
                .then(res => res.json())
                .then(res => {
                    document.getElementById('captchaText').innerText = res.captcha;
                    document.getElementById('captcha_token').value = res.token;
                })
                .catch(() => {
                    showError("Gagal load captcha");
                });
        }

        // ================= LOGIN =================
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let btn = document.getElementById('btnSubmit');
            let spinner = document.getElementById('btnLoading');

            btn.disabled = true;
            spinner.classList.remove('d-none');

            let data = {
                username: document.querySelector('[name="username"]').value,
                password: document.querySelector('[name="password"]').value,
                role: document.querySelector('[name="role"]').value,
                captcha: document.querySelector('[name="captcha"]').value,
                captcha_token: document.getElementById('captcha_token').value,

                remember: document.getElementById('remember').checked ? 1 : 0
            };
            // console.log(data)
            fetch('/api/postlogin', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: "same-origin",
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res.data);

                    if (res.status === 'success') {
                        localStorage.setItem('token', res.token);
                        window.location.href = res.redirect;
                    } else {
                        showError(res.message);
                        loadCaptcha();
                    }
                })
                .catch(() => {
                    showError("Terjadi kesalahan server");
                    loadCaptcha();
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        });

        // ================= HELPER =================
        function showError(msg) {
            let errorBox = document.getElementById('errorBox');
            errorBox.classList.remove('d-none');
            errorBox.innerText = msg;
        }

        function togglePassword() {
            let input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
