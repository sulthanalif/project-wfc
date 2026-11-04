<x-table.wrapper>
    <x-slot:header>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center whitespace-nowrap">NAMA PAKET</th>
            <th class="text-center whitespace-nowrap">TOTAL PESANAN</th>
        </tr>
    </x-slot:header>
    @if (empty($productDetails))
        <x-table.empty colspan="3" />
    @else
        @foreach ($productDetails as $item)
            <tr class="intro-x">
                <td>
                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3"> {{ $item['package'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 text-center">{{ $item['total_product'] }}</p>
                </td>
            </tr>
        @endforeach
    @endif
</x-table.wrapper>
