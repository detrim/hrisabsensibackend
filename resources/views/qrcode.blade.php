<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>QR Code Absensi</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            margin-top: 60px;
        }

        .box {
            border: 2px dashed #000;
            padding: 25px;
            display: inline-block;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
            margin-top: 5px;
            color: #555;
        }

        .qr {
            margin-top: 30px;
        }

        .info {
            margin-top: 20px;
            font-size: 14px;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="box">
            <div class="title">QR CODE ABSENSI</div>
            <div class="subtitle">Scan untuk melakukan absensi</div>

            <div class="qr">
                <img src="data:image/png;base64,{{ $qr }}" width="320">
            </div>

            <div class="info">
                <p><strong>Periode:</strong> {{ $text['periode'] }}</p>
                <p><strong>Berlaku sampai:</strong> {{ $text['expired'] }}</p>
            </div>
        </div>

        <div class="footer">
            <p>QR ini hanya berlaku untuk periode tersebut</p>
            <p>Jangan sebarkan QR ini ke pihak lain</p>
        </div>

    </div>

</body>

</html>
