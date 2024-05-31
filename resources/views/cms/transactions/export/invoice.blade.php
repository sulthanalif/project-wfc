<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header p {
            font-size: 14px;
            margin: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
        }

        .footer p {
            font-size: 12px;
            margin: 0;
        }

        .tanda-tangan {
            float: left;
            width: 30%;
            margin-top: 20px;
        }

        .tanda-tangan td {
            height: 100px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FAKTUR PEMBAYARAN ANGSURAN</h1>
            <p>PAKET SMART WFC</p>
            <p>Jl. Cipareuag No. 5, Sukadana<br>Kec. Cimanggung, Kab. Sumedang, Jawa Barat 45364<br>Telp. 081262760289</p>
        </div>


        <div class="">
            <p style="font-size: 12px;">
                WhatsApp Admin Paket : <br>
                <a href="https://wa.me/6282319961011">0823 1996 1011</a> ,
                <a href="https://wa.me/6282218799050">0822 1879 9050</a>
            </p>
        </div>
        <table class="table">
            <tr>
                <th>Nama Agen:</th>
                <td>{{ $order->agent->agentProfile->name }}</td>
            </tr>
            <tr>
                <th>No. Kontak:</th>
                <td>{{ $order->agent->agentProfile->phone_number ? $order->agent->agentProfile->phone_number : '-' }}</td>
            </tr>
            <tr>
                <th>Angsuran ke:</th>
                <td>{{ $payment->installment }}</td>
            </tr>
            <tr>
                <th>No Faktur:</th>
                <td>3959</td>
            </tr>
            <tr>
                <th>Pembayaran:</th>
                <td>{{ $payment->method }}</td>
            </tr>
            <tr>
                <th>Total Tagihan:</th>

                <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th>No.</th>
                <th>Tanggal Pembayaran</th>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
            <tr>
                <td>{{$payment->installment}}</td>
                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }}</td>
                <td>TUNAI</td>
                <td>Rp. {{ number_format($payment->pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan=3>JUMLAH ANGSURAN</td>
                <td>Rp. {{ number_format(array_sum(array_column($order->payment->toArray(), 'pay')), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan=3>SISA TAGIHAN</td>
                <td>Rp. {{ number_format($payment->remaining_payment, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            <table >
                <tr>

                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td align="center">Petugas</td>
                                <td align="center"> </td>
                                <td align="center">Agen</td>
                            </tr>
                            <tr>
                                <br> <br>
                            </tr>
                            <tr>
                                <td align="center"><span style="border-bottom: 1px solid black;">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span></td>
                                <td align="center"> </td>
                                <td align="center"><span style="border-bottom: 1px solid black;">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span></td>
                            </tr>
                        </table>
                    </td>
                    <td class="tanda-tangan">
                        <div style="text-decoration:underline;">
                            {{-- {{ $order->agent->agentProfile->name }} --}}
                        </div>

                    </td>
                </tr>
            </table>
            <p>(Simpan sebagai bukti pembayaran yang sah)</p>
        </div>
    </div>
</body>
</html>

