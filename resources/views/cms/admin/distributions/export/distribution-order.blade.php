<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A5 portrait;
            margin: 6mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            color: #000;
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
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
            line-height: 1.15;
        }

        .lh-md {
            line-height: 1.3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
        }

        th,
        td {
            padding: 4px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f8f8f8;
        }

        .table-borderless th,
        .table-borderless td {
            border: none;
            padding: 2px;
        }

        .table-dark th {
            background-color: #343a40;
            color: #fff;
        }

        .px-5 {
            padding-left: .75rem;
            padding-right: .75rem;
        }

        .mt-4 {
            margin-top: .5rem;
        }

        .mb-4 {
            margin-bottom: .5rem;
        }

        .text-bold {
            font-weight: bold;
        }

        .fh-cat {
            font-size: 10px;
        }

        a {
            text-decoration: none;
            color: black;
        }

        h3 {
            font-size: 12px;
            margin: 4px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="text-center lh-sm">
            <table class="table table-borderless m-0">
                <td class="text-start" style="align-items: center; display: flex;">
                    <h3 class="fw-bold">SURAT JALAN</h3>
                </td>
                <td class="text-end">
                    <h5 class="fw-bold"
                        style="border: 1px solid #000; padding: 4px 8px; display: inline-block; font-size: 9px;">
                        @if ($distribution->print_count == 1)
                            Asli
                        @else
                            Copy ke-{{ $distribution->print_count }}
                        @endif
                    </h5>
                </td>
            </table>

            <table class="table table-borderless">
                <thead>
                    <td class="fw-bold text-start">CV. WFC Jaya Barokah</td>
                    <td class="fw-bold text-end">PAKET SMART WFC</td>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">Dsn. Babakan Limus RT 001 RW 011,<br>Ds. Cihanjuang, Kec.
                            Cimanggung,<br>Kab. Sumedang,
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
                        @php
                            $details = $distribution->detail;
                            $data = [];
                            $tampilkan = [];
                            foreach ($details as $d) {
                                if (!$d->orderDetail->sub_agent_id) {
                                    $query = $d->orderDetail->order->agent->agentProfile;
                                    $data[] = [
                                        'name' => $query->name,
                                        'phone_number' => $query->phone_number ?? 'Nomer HP Belum Diisi',
                                        'address' => $query->address
                                            ? "{$query->address} RT {$query->rt} / RW {$query->rw}, {$query->village}, {$query->district}, {$query->regency}, {$query->province}"
                                            : 'Alamat Belum Diisi',
                                    ];
                                } else {
                                    $data[] = $d->orderDetail->subAgent->agentProfile;
                                }
                            }

                            foreach (array_filter($data) as $d) {
                                $tampilkan = $d;
                            }

                            if ($tampilkan == null) {
                                $tampilkan = [
                                    'name' => $details->first()->orderDetail->subAgent->name,
                                    'phone_number' => $details->first()->orderDetail->subAgent->phone_number,
                                    'address' => $details->first()->orderDetail->subAgent->address,
                                ];
                            }

                        @endphp
                        <td style="border: 1px solid #ccc; width: 50%; padding: 2px;">
                            Alamat Dituju : <br>
                            {!! $tampilkan['address'] !!}
                        </td>
                        <td class="text-start">
                            No. Surat Jalan: <b>{{ $distribution->distribution_number }}</b><br>
                            Tanggal: <b>{{ \Carbon\Carbon::parse($distribution->date)->format('d M Y') }}</b><br>
                            No. Polisi: <b>{{ $distribution->police_number }}</b><br>
                            Nama Pengemudi: <b>{{ $distribution->driver }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pemesan</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($distribution->detail->sortBy('order_number') as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $detail->orderDetail->sub_agent_id ? $detail->orderDetail->subAgent->name : $distribution->order->agent->agentProfile->name }}
                            </td>
                            <td>{{ $detail->orderDetail->product->name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->orderDetail->product->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <footer>
            <table>
                <tr>
                    <td class="text-center px-3">Tanda Terima <br> <br> <br></td>
                    <td class="fw-bold fst-italic px-1"></td>
                    <td class="text-center px-2" style="border: 2px solid #ccc;padding: 5px;">Pengemudi <br> <br> <br>
                    </td>
                    <td class="text-center px-2" style="border: 2px solid #ccc;padding: 5px;">Bag Checker <br> <br> <br>
                    </td>
                    <td class="text-center px-2" style="border: 2px solid #ccc;padding: 5px;">Gudang <br> <br> <br></td>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><span style="border-bottom: 1px solid black;">(
                            {{ $tampilkan['name'] }})</span>
                    </td>

                </tr>
            </table>
        </footer>
    </div>
</body>

</html>
