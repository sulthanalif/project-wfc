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
            <th class="whitespace-nowrap">BIAYA TRANSFORTASI PEMBELIAN</th>
            <th class="whitespace-nowrap">BIAYA TRANSFORTASI PENJUALAN</th>
            <th class="whitespace-nowrap">BIAYA TRANSFORTASI</th>
            <th class="whitespace-nowrap">BELANJA</th>
            <th class="whitespace-nowrap">UTANG DAGANG</th>
            <th class="whitespace-nowrap">BIAYA LISTRIK/TELPON</th>
            <th class="whitespace-nowrap">GAJI KARYAWAN</th>
            <th class="whitespace-nowrap">PERLENGKAPAN</th>
            <th class="whitespace-nowrap">PERALATAN</th>
            <th class="whitespace-nowrap">REWARD</th>
            <th class="whitespace-nowrap">GIFT</th>
            <th class="whitespace-nowrap">UTANG BANK</th>
            <th class="whitespace-nowrap">INVESTASI</th>
            <th class="whitespace-nowrap">HUTANG</th>
            <th class="whitespace-nowrap">PEMBAYARAN HUTANG</th>
            <th class="whitespace-nowrap">UMROH</th>
            <th class="whitespace-nowrap">PERMODALAN</th>
            <th class="whitespace-nowrap">BRI LINK/BUKALAPAK</th>
            <th class="whitespace-nowrap">PEGADAIAN</th>
            <th class="whitespace-nowrap">KREDIT AMANAH</th>
            <th class="whitespace-nowrap">BIAYA EVENT</th>
            <th class="whitespace-nowrap">BIAYA LAIN LAIN</th>
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
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Biaya Transportasi Pembelian' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Biaya Transportasi Penjualan' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Biaya Transportasi' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Belanja' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Utang Dagang' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Biaya Listrik/Telepon' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Gaji Karyawan' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Perlengkapan' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Peralatan' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Reward' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Gift' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Utang Bank' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Investasi' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Hutang' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Pembayaran Hutang' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Umroh' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Permodalan' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'BRI Link/Bukalapak' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Pegadaian' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Kredit Amanah' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Biaya Event' ? $data->amount : '0' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $data->spendingType->name == 'Biaya Lain-lain' ? $data->amount : '0' }}
                    </p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
