<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* .header {
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

        .table-border {
            width: 100%;
            border-collapse: collapse;
        }

        .table-border th, .table-border td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }

        .table-border th {
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
        } */
    </style>
</head>

<body>
    <div class="container">
        <section class="text-center lh-sm">
            <h3 class="fw-bold">FAKTUR PEMBAYARAN ANGSURAN</h3>
            <table class="table table-borderless">
                <thead>
                    <th class="h5 fw-bold text-start">CV. WIDA NUGRAHA</th>
                    <th class="h5 fw-bold text-end">PAKET SMART WFC</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">Jl. Cipareuag No. 5, Sukadana<br>Kec. Cimanggung, Kab. Sumedang, Jawa
                            Barat 45364<br>Telp. 081262760289</td>
                        <td class="text-end">WhatsApp Admin Paket : <br>
                            <a href="https://wa.me/6282319961011">0823 1996 1011</a> ,
                            <a href="https://wa.me/6282218799050">0822 1879 9050</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="lh-md">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="col-6">
                            Nama Agen: {{ $order->agent->agentProfile->name }}
                            <br>
                            No. Kontak:
                            {{ $order->agent->agentProfile->phone_number ? $order->agent->agentProfile->phone_number : '-' }}
                            <br>
                            Pembayaran: {{ $payment->method }}
                        </td>
                        <td class="col-6">
                            Angsuran ke: {{ $payment->installment }}
                            <br>
                            No Faktur: INVS990T02062024-3
                            <br>
                            Total Tagihan: Rp. {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section>
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <th>No.</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>{{ $payment->installment }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }}</td>
                        <td>TUNAI</td>
                        <td>Rp. {{ number_format($payment->pay, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=3 class="fw-bold">JUMLAH ANGSURAN</td>
                        <td class="text-center">Rp. {{ number_format($payment->pay, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan=3 class="fw-bold">TOTAL DIANGSUR</td>
                        <td class="text-center">Rp. {{ number_format(array_sum(array_column($order->payment->toArray(), 'pay')), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan=3 class="fw-bold">SISA TAGIHAN</td>
                        <td class="text-center">Rp. {{ number_format($payment->remaining_payment, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <footer>
            <table>
                <tr>
                    <td class="text-center px-5">Petugas <br> <br> <br></td>
                    <td class="text-center px-5">Agen <br> <br> <br></td>
                    <td class="fw-bold fst-italic px-5">(Simpan sebagai bukti pembayaran yang sah)<br> <br> <br></td>
                </tr>
                <tr>
                    <td class="text-center"><span style="border-bottom: 1px solid black;">(
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span>
                    </td>
                    <td class="text-center"><span style="border-bottom: 1px solid black;">(
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span>
                    </td>
                </tr>
            </table>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
