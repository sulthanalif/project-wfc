<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="text-center whitespace-nowrap">#</th>
                <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                <th class="text-center whitespace-nowrap">NAMA PRODUK</th>
                {{-- <th class="text-center whitespace-nowrap">TOTAL PRODUK</th> --}}
                <th class="text-center whitespace-nowrap">TOTAL DISTRIBUSI</th>
            </tr>
        </thead>
        <tbody>
            @if (!$distributions)
                <tr>
                    <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                </tr>
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
                        {{-- <td>
                            <p class="text-slate-500 text-center">{{ $distribution['total_order'] }}</p>
                        </td> --}}
                        <td>
                            <p class="text-slate-500 text-center">{{ $distribution['total_distribution'] }}</p>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
