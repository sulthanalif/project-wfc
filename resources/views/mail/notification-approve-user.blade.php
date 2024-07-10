<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Telah Aktif</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #444;
        }
        .content {
            margin: 20px 0;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px auto;
            background-color: #cfcfcf;
            color: #000000;
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #858585;
            color: #000000;
        }
        a {
            color: #000000;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .button {
                width: 100%;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Akun Telah Aktif</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Selamat! Registrasi akun Anda telah berhasil. Silakan login menggunakan akun yang telah Anda daftarkan atau klik tombol di bawah ini:</p>
            <a href="{{ route('dashboard-agent') }}" class="button">Klik di sini</a>
            <p>Jika ada pertanyaan, silakan hubungi +62822-1879-9050</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Smart WFC Jabar. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
