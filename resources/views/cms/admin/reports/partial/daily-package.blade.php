<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="text-center whitespace-nowrap">#</th>
                <th class="text-center whitespace-nowrap">NAMA PAKET</th>
                <th class="text-center whitespace-nowrap">TOTAL PESANAN</th>
            </tr>
        </thead>
        <tbody>
            @if (!$productDetails)
                <tr>
                    <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                </tr>
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
        </tbody>
    </table>
</div>
