<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HRIS Pegawai & Absensi System</title>

    <!-- Bootstrap 5 CDN -->
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
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/shortcut.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/shortcut.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/shortcut.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/shortcut.png') }}">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">

                <h4 class="text-center mb-4">Login</h4>

                {{-- Error --}}
                @if ($errors->any())
                    <script>
                        // stop loading jika ada error
                        document.getElementById("btnLogin").disabled = false;
                        document.getElementById("spinner").classList.add("d-none");
                        document.getElementById("btnText").innerText = "Login";
                    </script>
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ url('postlog') }}">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-3">
                        <label class="form-label">Username / Email / Phone</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                            required>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                👁️
                            </button>
                        </div>
                    </div>

                    {{-- Role Dropdown --}}
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="1">Admin</option>
                            <option value="2">Manajer HRD</option>
                            <option value="3">Staf HRD</option>
                        </select>
                    </div>

                    {{-- CAPTCHA --}}
                    <div class="mb-3">
                        <label class="form-label">Captcha</label>

                        {{-- Tampilkan captcha --}}
                        <div class="row align-items-center mb-3">

                            <!-- CAPTCHA -->
                            <div class="col-md-4 mb-2 mb-md-0">
                                <strong class="bg-secondary text-white px-3 py-2 rounded d-block text-center">
                                    {{ session('captcha') }}
                                </strong>
                            </div>

                            <!-- INPUT -->
                            <div class="col-md-8">
                                <input type="text" name="captcha" class="form-control" placeholder="Masukkan captcha"
                                    required>
                            </div>

                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                    {{-- Button --}}
                    <div class="d-grid">
                        <button type="submit" id="btnLogin" class="btn btn-primary">
                            <span id="btnText">Login</span>

                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>

                </form>
                <div class="alert alert-info mt-3 text-center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalAkunDemo"
                        class="text-decoration-underline fw-semibold">
                        Klik lihat akun demo
                    </a>
                    <br>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#rbacModal"
                        class="text-decoration-underline fw-semibold">
                        📘 Tekan untuk Pengertian Sistem
                    </a>
                </div>

            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="rbacModal" tabindex="-1" aria-labelledby="rbacModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="rbacModalLabel">
                            Role-Based Access Control (RBAC)
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Penjelasan -->
                        <h6>📘 Pengertian Sistem</h6>
                        <p>
                            Sistem ini menggunakan Role-Based Access Control (RBAC) untuk mengatur hak akses pengguna
                            berdasarkan peran (role).
                            Setiap role memiliki akses berbeda terhadap modul sistem seperti user, absensi, pegawai, dan
                            log aktivitas.
                        </p>

                        <hr>

                        <!-- Tabel -->
                        <h6>📊 Tabel Hak Akses</h6>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Aktivitas</th>
                                        <th>Superadmin</th>
                                        <th>Manager HRD</th>
                                        <th>Admin HRD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Login / Logout</td>
                                        <td>Y</td>
                                        <td>Y</td>
                                        <td>Y</td>
                                    </tr>
                                    <tr>
                                        <td>Kelola Role</td>
                                        <td>Y</td>
                                        <td>X</td>
                                        <td>X</td>
                                    </tr>
                                    <tr>
                                        <td>Kelola User</td>
                                        <td>CRUD</td>
                                        <td>RO / UO</td>
                                        <td>RO / UO</td>
                                    </tr>
                                    <tr>
                                        <td>Data Pegawai</td>
                                        <td>X</td>
                                        <td>R</td>
                                        <td>CRUD</td>
                                    </tr>
                                    <tr>
                                        <td>Absensi</td>
                                        <td>X</td>
                                        <td>X</td>
                                        <td>CRUD</td>
                                    </tr>
                                    <tr>
                                        <td>Setting Tunjangan Transpot</td>
                                        <td>X</td>
                                        <td>X</td>
                                        <td>CRUD</td>
                                    </tr>
                                    <tr>
                                        <td>Tunjangan Transpot Pegawai</td>
                                        <td>X</td>
                                        <td>RO</td>
                                        <td>RO</td>
                                    </tr>
                                    <tr>
                                        <td>Log Activity</td>
                                        <td>R</td>
                                        <td>X</td>
                                        <td>X</td>
                                    </tr>
                                    <tr>
                                        <td>Online Users Monitor</td>
                                        <td>Y</td>
                                        <td>X</td>
                                        <td>X</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- Keterangan -->
                        <h6>🔑 Keterangan</h6>
                        <ul>
                            <li><b>C</b> = Create</li>
                            <li><b>R</b> = Read</li>
                            <li><b>U</b> = Update</li>
                            <li><b>D</b> = Delete</li>
                            <li><b>RO</b> = Read Only, hanya bisa membaca data yang dia buat atau hanya diperuntukkan
                                dirinya</li>
                            <li><b>UO</b> = Update Only, bisa memperbarui atau mengubah data terbatas</li>
                            <li><b>X</b> = Tidak ada akses</li>
                            <li><b>Y</b> = Akses penuh, tanpa perlu aksi CRUD</li>
                        </ul>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <br>
        {{-- Info Akun Demo --}}
        <div class="modal fade" id="modalAkunDemo" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Akun Demo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3 border rounded bg-light">
                            <div class="small">
                                <p class="mb-1">
                                    <strong>Super Admin</strong><br>
                                    Username: superadmin <br>
                                    Role: Admin <br>
                                    Password: Password123
                                </p>
                                <hr class="my-2">
                                <p class="mb-0">
                                    <strong>Admin HRD</strong><br>
                                    Username: adminhrd <br>
                                    Role: Staf HRD <br>
                                    Password: Password123
                                </p>
                                <hr class="my-2">
                                <p class="mb-0">
                                    <strong>Manager HRD</strong><br>
                                    Username: managerhrd <br>
                                    Role: Manajer HRD <br>
                                    Password: Password123
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    function togglePassword() {
        let input = document.getElementById('password');

        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
    const form = document.querySelector("form");
    const btn = document.getElementById("btnLogin");
    const spinner = document.getElementById("spinner");
    const btnText = document.getElementById("btnText");

    form.addEventListener("submit", function() {
        // aktifkan loading
        btn.disabled = true;
        spinner.classList.remove("d-none");
        btnText.innerText = "Memproses...";
    });
</script>

</html>
