<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-nowrap">Total Terbayar</th>
            @if (isset($stats['remaining_pay']))
                <th class="whitespace-nowrap">Total Belum Terbayar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr class="intro-x">
            <td>
                <p class="text-slate-500 flex items-center mr-3">{{ $stats['pay'] }} </p>
            </td>
            @if (isset($stats['remaining_pay']))
                <td>
                    <p class="text-slate-500 flex items-center mr-3">{{ $stats['remaining_pay'] }} </p>
                </td>
            @endif
        </tr>
    </tbody>
</table>
<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">Nama Agen</th>
            <th class="text-center whitespace-nowrap">Nomer Order</th>
            <th class="text-center whitespace-nowrap">Jumlah</th>
            <th class="text-center whitespace-nowrap">Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
            <tr class="intro-x">

                <td>
                    <p class="text-center mr-3">
                        {{ $payment->order->agent->agentProfile->name }} </p>
                </td>
                <td>
                    <p class="text-slate-500 text-center mr-3"> {{ $payment->order->order_number }}
                    </p>
                </td>
                <td>
                    <p class="text-slate-500 flex text-center"> Rp.
                        {{ number_format($payment->pay, 0, ',', '.') }} </p>
                </td>
                <td>
                    <p class="text-slate-500 text-center items-center mr-3"> {{ $payment->created_at }}
                    </p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
