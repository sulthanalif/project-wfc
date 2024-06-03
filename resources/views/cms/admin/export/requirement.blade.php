<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Sub Produk</th>
            <th class="whitespace-nowrap">Total Harga</th>

        </tr>
    </thead>
    <tbody>
        <tr class="intro-x">
            <td>
                <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalSubProductAll'] }} </p>
            </td>
            <td>
                <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalPriceAll'] }} </p>
            </td>

        </tr>
    </tbody>
</table>
<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">NAMA SUB PRODUK</th>
            <th class="text-center whitespace-nowrap">JUMLAH</th>
            <th class="text-center whitespace-nowrap">SATUAN</th>
            <th class="text-center whitespace-nowrap">TOTAL HARGA</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datasubs as $sub)
            <tr class="intro-x">
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $sub['name'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $sub['qty'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $sub['unit'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $sub['price'] }} </p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
