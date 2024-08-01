<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Pemasukan</th>
        </tr>
    </thead>
    <tbody>
        <tr class="intro-x">
            <td>
                <p class="text-slate-500 flex items-center mr-3">Rp.
                    {{ number_format($datas->sum('amount'), 0, ',', '.') }}</p>
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">TANGGAL</th>
            <th class="text-center whitespace-nowrap">KETERANGAN</th>
            <th class="text-center whitespace-nowrap">CASH</th>
            <th class="text-center whitespace-nowrap">BRI</th>
            <th class="text-center whitespace-nowrap">BCA</th>
            <th class="text-center whitespace-nowrap">MANDIRI</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr class="intro-x">
                <td>
                    <p class="text-center mr-3">
                        {{ $data->date }}
                    </p>
                </td>
                <td>
                    <p class="text-center mr-3">
                        {{ $data->information }}
                    </p>
                </td>
                <td>
                    <p class="text-center mr-3">
                        {{ $data->method == 'tunai' ? $data->amount : 0 }}
                    </p>
                </td>
                <td>
                    <p class="text-center mr-3">
                        {{ $data->method == 'transfer' && $data->bank == 'BRI' ? $data->amount : 0 }}
                    </p>
                </td>
                <td>
                    <p class="text-center mr-3">
                        {{ $data->method == 'transfer' && $data->bank == 'BCA' ? $data->amount : 0 }}
                    </p>
                </td>
                <td>
                    <p class="text-center mr-3">
                        {{ $data->method == 'transfer' && $data->bank == 'Mandiri' ? $data->amount : 0 }}
                    </p>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
