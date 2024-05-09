<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="whitespace-nowrap">NAMA BARANG/PAKET</th>
            <th class="whitespace-nowrap">SATUAN</th>
            <th class="whitespace-nowrap">JUMLAH</th>
            {{-- <th class="whitespace-nowrap">NOMER ORDER</th> --}}
            <th class="whitespace-nowrap">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @if ($distribution->order->detail->isEmpty())
            <tr>
                <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
            </tr>
        @else
            @foreach ($distribution->order->detail as $order)
                <tr class="intro-x">
                    <td>
                        <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3"> {{ $order->name }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3"> pcs </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3"> {{ $order->qty }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3"> - </p>
                    </td>
                    
                </tr>

            @endforeach
        @endif
    </tbody>
</table>