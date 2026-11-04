<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">#</th>
            <th class="whitespace-nowrap">NAMA PEMINJAM</th>
            <th class="whitespace-nowrap">JUMLAH</th>
            <th class="whitespace-nowrap">METODE</th>
            <th class="whitespace-nowrap">STATUS PEMBAYARAN</th>
            <th class="whitespace-nowrap">TANGGAL</th>
            <th class="whitespace-nowrap">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loans as $loan)
            <tr class="intro-x">
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $loop->iteration }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $loan->borrower_name }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $loan->amount }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">
                        {{ $loan->method }} {{ $loan->method == 'Transfer' && $loan->bankOwner ? '(' . $loan->bankOwner->name . ' - ' . $loan->bankOwner->account_number . ')' : '' }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $loan->status_payment }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $loan->created_at }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $loan->description }}</p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
