<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Setoran</th>
        </tr>
    </thead>
    <tbody>
                <tr class="intro-x">
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $stats['totalDeposit'] }} </p>
                    </td>
                </tr>  
    </tbody>
</table>
<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">NAMA AGEN</th>
            <th class="whitespace-nowrap">SETORAN</th>
        </tr>
    </thead>
    <tbody>
            @foreach ($datas as $agent)
                <tr class="intro-x">
                   
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $agent['agent_name'] }} </p>
                    </td>
                    <td>
                        <p class="text-slate-500 flex items-center mr-3">{{ $agent['total_deposit'] }} </p>
                    </td>      
                </tr>  
            @endforeach
    </tbody>
</table>