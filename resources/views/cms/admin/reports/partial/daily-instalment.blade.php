<x-table.wrapper>
    <x-slot:header>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center whitespace-nowrap">NAMA AGEN</th>
            <th class="text-center whitespace-nowrap">NOMER ORDER</th>
            <th class="text-center whitespace-nowrap">JUMLAH</th>
            <th class="text-center whitespace-nowrap">WAKTU</th>
        </tr>
    </x-slot:header>
    @if (empty($payments))
        <x-table.empty colspan="5" />
    @else
        @foreach ($payments as $payment)
            <tr class="intro-x">
                <td>
                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                </td>
                <td>
                    <p class="text-center mr-3">
                        {{ $payment->order?->agent?->agentProfile?->name ?? 'N/A' }} </p>
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
    @endif
</x-table.wrapper>
