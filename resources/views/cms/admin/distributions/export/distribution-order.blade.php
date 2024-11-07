<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    </style>
</head>
<body>
    <div class="container">
        <section class="text-center lh-sm">
            <table class="table table-borderless">
                <thead>
                    <th class="h5 fw-bold text-start">PAKET SMART WFC JABAR</th>
                    <th class="h5 fw-bold text-end">Surat Jalan</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">{{ $contact->address }}
                            @foreach ($contact->numbers as $number)
                            <br>Telp. {{ $number->number }}</td>
                            @endforeach

                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc;">
                            Alamat Dituju : <br>
                            {{ $distribution->order->agent->agentProfile->address ? $distribution->order->agent->agentProfile->address . " RT " . $distribution->order->agent->agentProfile->rt . " / RW " . $distribution->order->agent->agentProfile->rw . ", " . $distribution->order->agent->agentProfile->village . ", " . $distribution->order->agent->agentProfile->district . ", " . $distribution->order->agent->agentProfile->regency . ", " . $distribution->order->agent->agentProfile->province : 'Alamat Belum Diisi' }}
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

            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($distribution->detail as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->orderDetail->product->name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->orderDetail->product->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table>
                <tr>
                    <td class="text-center px-5">Tanda Terima <br> <br> <br></td>

                    <td class="fw-bold fst-italic px-5">
                    <td class="text-center px-2" style="border: 2px solid #ccc;padding: 5px;">Pengemudi <br> <br> <br></td>
                    <td class="text-center px-2" style="border: 2px solid #ccc;padding: 5px;">Bag Checker <br> <br> <br></td>
                    <td class="text-center px-2" style="border: 2px solid #ccc;padding: 5px;">Gudang <br> <br> <br></td>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><span style="border-bottom: 1px solid black;">(
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span>
                    </td>

                </tr>
            </table>
        </section>
    </div>
</body>
</html>
