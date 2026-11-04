<x-table.wrapper>
    <x-slot:header>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center whitespace-nowrap">NAMA AGEN</th>
            <th class="text-center whitespace-nowrap">NAMA PRODUK</th>
            <th class="text-center whitespace-nowrap">TOTAL DISTRIBUSI</th>
        </tr>
    </x-slot:header>
    @if (empty($distributionData))
        <x-table.empty colspan="4" />
    @else
        @foreach ($distributionData as $distribution)
            <tr class="intro-x">
                <td>
                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center mr-3"> {{ $distribution['agent_name'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 flex items-center justify-center mr-3"> {{ $distribution['package'] }} </p>
                </td>
                <td>
                    <p class="text-slate-500 text-center">{{ $distribution['total_distribution'] }}</p>
                </td>
            </tr>
        @endforeach
    @endif
</x-table.wrapper>
