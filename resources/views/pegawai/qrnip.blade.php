<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial;
        }

        .card {
            width: 350px;
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
        }

        .foto {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .qr {
            margin-top: 15px;
            width: 180px;
        }

        .nip {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="card">

    {{-- FOTO PEGAWAI --}}
    <img class="foto" src="{{ public_path('storage/'.$pegawai->foto) }}">

    <h3>{{ $pegawai->nama }}</h3>

    <div class="nip">NIP: {{ $pegawai->nip }}</div>

    {{-- QR CODE --}}
    <img class="qr" src="data:image/png;base64,{{ $qr }}">

</div>

</body>
</html>
