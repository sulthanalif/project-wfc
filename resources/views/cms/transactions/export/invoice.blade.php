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

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fst-italic {
            font-style: italic;
        }

        .lh-sm {
            line-height: 1.25;
        }

        .lh-md {
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f8f8f8;
        }

        .table-borderless th, .table-borderless td {
            border: none;
        }

        .table-dark th {
            background-color: #343a40;
            color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .px-5 {
            padding-left: 3rem;
            padding-right: 3rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .text-bold {
            font-weight: bold;
        }

        .fh-cat {
            font-size: 12px;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="text-center lh-sm">
            <h3 class="fw-bold">FAKTUR PEMBAYARAN ANGSURAN</h3>
            <table class="table table-borderless">
                <thead>
                    <td class="fw-bold text-start">CV. WIDA NUGRAHA</td>
                    <td class="fw-bold text-end">PAKET SMART WFC</td>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">Jl. Cipareuag No. 5, Sukadana<br>Kec. Cimanggung, Kab. Sumedang, <br>
                            Jawa Barat 45364<br>Telp. 081262760289</td>
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
                            No Faktur: {{ $payment->invoice_number }}
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
                    <th>Tanggal</th>
                    <th>Metode</th>
                    <th width="30%">Keterangan</th>
                    <th>Nominal</th>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>{{ $payment->installment }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}</td>
                        <td>{{ strtoupper($payment->method) }}</td>
                        <td>{{ $payment->note ?? '-' }}</td>
                        <td>Rp. {{ number_format($payment->pay, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=4 class="fw-bold">JUMLAH ANGSURAN</td>
                        <td class="text-center">Rp. {{ number_format($payment->pay, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan=4 class="fw-bold">TOTAL DIANGSUR</td>
                        <td class="text-center">Rp. {{ number_format(array_sum(array_column($order->payment->toArray(), 'pay')), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 class="fw-bold">SISA TAGIHAN</td>
                        <td class="text-center">Rp. {{ number_format($payment->remaining_payment, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <footer>
            <table class="table-borderless">
                <tr>
                    <td class="text-center px-5">Petugas <br> <br> <br></td>
                    <td class="text-center px-5">Agen <br> <br> <br></td>
                    <td class="fw-bold fst-italic px-5 fh-cat">(Simpan sebagai bukti pembayaran yang sah)<br> <br> <br></td>
                </tr>
                <tr>
                    <td class="text-center"><span style="border-bottom: 1px solid black;">(
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span>
                    </td>
                    <td class="text-center"><span style="border-bottom: 1px solid black;">(
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span>
                    </td>
                </tr>
            </table>
        </footer>
    </div>
</body>

</html>
