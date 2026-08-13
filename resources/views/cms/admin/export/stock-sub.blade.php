<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center whitespace-nowrap">ITEM</th>
            <th class="text-center whitespace-nowrap">SATUAN</th>
            <th class="text-center whitespace-nowrap">JUMLAH STOK</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $data)
            <tr class="intro-x">
                <td>
                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $data['name'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $data['unit'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $data['stock'] ?? 0 }} </p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
