<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #e9ecef;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f9;
            color: #444;
        }

        td {
            word-wrap: break-word;
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

            table,
            th,
            td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Berhasil</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Selamat! Pembayaran Anda telah berhasil, dengan detail sebagai berikut:</p>
            <table>
                <tr>
                    <th>Nomor Pesanan</th>
                    <td>{{ $payment->order->order_number }}</td>
                </tr>
                <tr>
                    <th>Nomor Invoice</th>
                    <td>{{ $payment->invoice_number }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>{{ strtoupper($payment->order->payment_status) }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ strtoupper($payment->method) }}</td>
                </tr>
                <tr>
                    <th>Jumlah Bayar</th>
                    <td>Rp. {{ number_format($payment->pay, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Jumlah Sisa Pembayaran</th>
                    <td>Rp. {{ number_format($payment->remaining_payment, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td>{!! $payment->note ?? '-' !!}</td>
                </tr>
            </table>
            <p>Untuk mengunduh Bukti Pembayaran silahkan klik tombol di bawah ini:</p>
            <a class="button" target="_blank"
                href="{{ route('getInvoice', ['order' => $payment->order, 'payment' => $payment]) }}">Download
                Invoice</a>
            <p>Jika ada pertanyaan, silakan hubungi +62822-1879-9050</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Smart WFC Jabar. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
