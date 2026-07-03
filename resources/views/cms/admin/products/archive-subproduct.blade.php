<div class="box p-5 rounded-md">
    <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
        <div class="font-medium text-base truncate">Sub Barang</div>
    </div>
    <div class="overflow-auto lg:overflow-visible -mt-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="whitespace-nowrap !py-5">NAMA BARANG</th>
                    <th class="whitespace-nowrap text-center">JUMLAH</th>
                    <th class="whitespace-nowrap text-center">SATUAN</th>
                    <th class="whitespace-nowrap text-center">HARGA</th>
                </tr>
            </thead>
            <tbody>
                @if ($subProducts->count() <= 0)
                    <tr>
                        <td colspan="5" class="font-medium whitespace-nowrap text-center">Tidak Ada
                            Sub Barang Pada Produk Ini!</td>
                    </tr>
                @else
                    @foreach ($subProducts as $sub)
                        <tr>
                            <td class="!py-4 whitespace-nowrap">{{ $sub->subProduct?->name ?? '-' }}</td>
                            <td class="text-center">{{ $sub->amount ?? '-' }}</td>
                            <td class="text-center">{{ $sub->subProduct?->unit ?? '-' }}</td>
                            <td class="text-center">Rp. {{ number_format($sub->subProduct->price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>