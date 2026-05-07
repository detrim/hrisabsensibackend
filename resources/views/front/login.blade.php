<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hadirku</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-image: url('/img/bg.png');
            padding: 15px;
        }


        /* HEADER LUAR CARD */
        .app-header {
            text-align: center;
            margin-bottom: 15px;
            color: #fff;
        }

        .app-icon {
            font-size: 44px;
            line-height: 1;
            margin-bottom: 6px;
        }

        .app-title {
            font-size: 22px;
            font-weight: bold;
        }

        .app-subtitle {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 3px;
        }

        .login-container {
            width: 100%;
            max-width: 380px;
            background: #fff;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 18px;
            color: #333;
        }

        .alert {
            display: none;
            background: #ffe5e5;
            color: #b00020;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .input-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #4facfe;
        }

        .password-wrapper {
            display: flex;
            gap: 6px;
        }

        .password-wrapper input {
            flex: 1;
        }

        .toggle-btn {
            width: 50px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: #eee;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .remember input {
            width: auto;
        }

        .remember label {
            margin: 0;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            background: #4facfe;
            color: #fff;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #00c6ff;
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .footer-text {
            text-align: center;
            margin-top: 14px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>

    <!-- HEADER DI LUAR CARD -->
    <div class="app-header">
        <div class="app-icon">📌</div>
        <div class="app-title">Hadirku</div>
        <div class="app-subtitle">Sistem Absensi Pegawai</div>
    </div>

    <div class="login-container">

        <h2>Login</h2>

        <div id="errorBox" class="alert"></div>

        <form id="loginForm">

            <div class="input-group">
                <label>Username / Email / Phone</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-btn" onclick="togglePassword()">
                        👁️
                    </button>
                </div>
            </div>

            <div class="remember">
                <input type="checkbox" name="remember" id="remember" value="1">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn" id="btnSubmit">Login</button>

        </form>

        <div class="footer-text">
            © <span id="year"></span> Aplikasi HRIS
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            if (!input) return;
            input.type = (input.type === 'password') ? 'text' : 'password';
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('btnSubmit');
            const errorBox = document.getElementById('errorBox');

            btn.disabled = true;
            btn.innerText = 'Loading...';
            errorBox.style.display = 'none';

            const data = {
                username: document.querySelector('[name="username"]').value,
                password: document.querySelector('[name="password"]').value,
                remember: document.getElementById('remember').checked ? 1 : 0
            };

            fetch('/api/posthadirku', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        localStorage.setItem('token', res.token);
                        window.location.href = res.redirect;
                    } else {
                        errorBox.style.display = 'block';
                        errorBox.innerText = res.message;
                    }
                })
                .catch(() => {
                    errorBox.style.display = 'block';
                    errorBox.innerText = 'Terjadi kesalahan server';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerText = 'Login';
                });
        });

        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>

</html>
