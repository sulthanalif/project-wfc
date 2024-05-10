<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Pemasukan</th>
            <th class="whitespace-nowrap">Total Setoran</th>
            <th class="whitespace-nowrap">Total Sisa Pembayaran</th>
        </tr>
    </thead>
    <tbody>
                <tr class="intro-x">
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalPriceOrderAll'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalDepositAll'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalRemainingAll'] }} </p>
                    </td> 
                </tr>  
    </tbody>
</table>
<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">NAMA AGEN</th>
            <th class="whitespace-nowrap">PEMASUKAN</th>
            <th class="whitespace-nowrap">SETORAN</th>
            <th class="whitespace-nowrap">SISA</th>
            {{-- <th class="text-center whitespace-nowrap">AKSI</th> --}}
        </tr>
    </thead>
    <tbody>
            @foreach ($datas as $agent)
                <tr class="intro-x">
                   
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $agent['agent_name'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $agent['total_price_order'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $agent['total_deposit'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $agent['total_remaining_payment'] }} </p>
                    </td>
                    
                </tr>  
            @endforeach
    </tbody>
</table>