<x-table.wrapper>
    <x-slot:header>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center whitespace-nowrap">NAMA AGEN</th>
            <th class="text-center whitespace-nowrap">TOTAL PRODUK</th>
            <th class="text-center whitespace-nowrap">TOTAL HARGA</th>
        </tr>
    </x-slot:header>
    @if (empty($agentOrders))
        <x-table.empty colspan="4" />
    @else
        @foreach ($agentOrders as $agent)
            <tr class="intro-x">
                <td>
                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3"> {{ $agent['agent_name'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 text-center">{{ $agent['total_product'] }}</p>
                </td>
                <td>
                    <p class="text-slate-500 text-center"> Rp.
                        {{ number_format($agent['total_price'], 0, ',', '.') }} </p>
                </td>
            </tr>
        @endforeach
    @endif
</x-table.wrapper>
