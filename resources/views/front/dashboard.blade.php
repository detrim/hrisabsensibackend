<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner Hadirku</title>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toast simple -->
    <style>
        body {
            font-family: Arial;
            background-image: url('/img/bg.png');
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            color: #fff;
        }

        .card {
            background: #fff;
            color: #000;
            padding: 15px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
        }

        #reader {
            width: 100%;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: #fff;
            padding: 12px 18px;
            border-radius: 8px;
            display: none;
        }

        .success {
            background: #16a34a;
        }

        .error {
            background: #dc2626;
        }

        .top-bar {
            width: 100%;
            max-width: 420px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            font-size: 14px;
            color: #111;
        }

        .user-info small {
            font-size: 12px;
            color: #666;
        }

        .logout-btn {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
            cursor: pointer;
        }

        /* QR CARD */
        .card {
            background: #fff;
            color: #000;
            padding: 15px;
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
        }

        /* MOBILE OPTIMIZE */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .top-bar {
                flex-direction: row;
                padding: 10px;
            }

            .logout-btn {
                padding: 6px 10px;
                font-size: 12px;
            }

            h2 {
                font-size: 18px;
            }

            .app-header {
                text-align: center;
                margin-bottom: 15px;
                color: #fff;
            }

            .app-icon {
                font-size: 44px;
                line-height: 1;
                margin-bottom: 10px;
                margin-top: 10px;
            }

            .app-title {
                font-size: 22px;
                font-weight: bold;
            }

            .app-subtitle {
                font-size: 13px;
                opacity: 0.9;
                margin-top: 3px;
                text-align: center;
            }

        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="user-info">
            👤 {{ $user->name ?? 'User' }}
        </div>
        <button onclick="logout()" class="logout-btn">
            Logout
        </button>
    </div>

    <div class="card">
        <div class="app-subtitle">Sistem Absensi Pegawai</div>
        <p><b>Employee ID:</b> {{ $user->employee_id }}</p>

        <div id="reader"></div>
    </div>

    <div id="toast" class="toast"></div>
    @if (session()->has('api_token'))
        <script>
            const token = "{{ session('api_token') }}";
        </script>
    @endif
    <script>
        const toast = document.getElementById("toast");

        function showToast(message, type = "success") {
            toast.innerText = message;
            toast.className = "toast " + type;
            toast.style.display = "block";
            setTimeout(() => {
                toast.style.display = "none";
            }, 3000);
        }

        function onScanSuccess(decodedText) {

            fetch("/api/absensi/scan", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Bearer " + token
                    },
                    body: JSON.stringify({
                        qr_code: decodedText,
                        employee_id: "{{ $user->employee_id }}"
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        showToast(res.message, "success");
                    } else {
                        showToast(res.message, "error");
                    }
                })
                .catch(() => {
                    showToast("Server error", "error");
                });
        }

        function onScanError(error) {
            console.log("Scan error:", error);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: 250
            }
        );

        html5QrcodeScanner.render(onScanSuccess, onScanError);


        function logout() {
            Swal.fire({
                title: 'Yakin mau logout?',
                text: "Kamu akan keluar dari sistem",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {


                    fetch("/api/logout", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "Authorization": "Bearer " + token
                            }
                        })
                        .then(() => {

                            Swal.fire({
                                title: 'Berhasil Logout',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                window.location.href = "/hadirku";
                            }, 1200);
                        })
                        .catch(() => {
                            Swal.fire({
                                title: 'Gagal logout',
                                icon: 'error'
                            });
                        });
                }
            });
        }
    </script>

</body>

</html>
