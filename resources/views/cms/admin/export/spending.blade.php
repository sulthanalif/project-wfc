<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Pengeluaran</th>
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
            <th class="whitespace-nowrap">TANGGAL</th>
            <th class="whitespace-nowrap">KETERANGAN</th>
            <th class="whitespace-nowrap">METODE</th>
            @foreach ($types as $type)
            <th class="whitespace-nowrap">{{ $type->name }}</th>
            @endforeach

            {{-- <th class="text-center whitespace-nowrap">AKSI</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr class="intro-x">
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $data->date }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $data->information }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->method == 'tunai' ? 'Tunai' : 'Transfer (' . $data->bank . ')' }}
                    </p>
                </td>
                @foreach ($types as $type)
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == $type->name ? $data->amount : '0' }}
                    </p>
                </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
