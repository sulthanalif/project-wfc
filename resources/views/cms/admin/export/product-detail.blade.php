<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Produk/Paket</th>

        </tr>
    </thead>
    <tbody>
                <tr class="intro-x">
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalProductAll'] }} </p>
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
            <th class="whitespace-nowrap">NAMA PAKET</th>
            <th class="whitespace-nowrap">TOTAL PESANAN</th>
        </tr>
    </thead>
    <tbody>
            @foreach ($datas as $item)
                <tr class="intro-x">
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $item['package'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $item['total_product'] }} </p>
                    </td>      
                </tr>  
            @endforeach
    </tbody>
</table>