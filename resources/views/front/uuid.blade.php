<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner Hadirku</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

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
            max-width: 420px;
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
    </style>
</head>

<body>

    <h3>📌 Scan QR Absensi</h3>

    <div class="card">
        <div id="reader"></div>
    </div>

    <div id="toast" class="toast"></div>

    <script>
        const toast = document.getElementById("toast");

        function showToast(message, type = "success") {
            toast.innerText = message;
            toast.className = "toast " + type;
            toast.style.display = "block";

            setTimeout(() => {
                toast.style.display = "none";
            }, 1000);
        }

        function onScanSuccess(decodedText) {

            let qrData;

            try {
                qrData = JSON.parse(decodedText);
            } catch (e) {
                showToast("QR tidak valid (bukan JSON)", "error");
                return;
            }

            // ambil data & signature
            let qr_code = JSON.stringify(qrData.data);
            let signature = qrData.signature;

            fetch("/api/absensi/scanuuid", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        qr_code: qr_code,
                        signature: signature
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
            // silent
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: 250
            }
        );

        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>

</body>

</html>
