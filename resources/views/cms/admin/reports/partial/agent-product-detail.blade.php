@extends('cms.layouts.app', [
    'title' => 'Daftar Agen Rincian Perpaket',
])

@section('content')
    <h2 class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Daftar Agen Rincian Perpaket #{{ $product->first()->name ?? '-' }}
            </h2>
            <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                <a href="{{ route('rproductDetail') }}" class="btn px-2 box"><i data-lucide="arrow-left"
                        class="w-4 h-4"></i></a>
            </div>
        </div>
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                        <th class="text-center whitespace-nowrap">TOTAL PESANAN</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($orders as $order)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-500 items-center">
                                        {{ $order->agent->agentProfile->name ?? '-' }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center">{{ $order->totalProduct }}</p>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-center">TOTAL PESANAN PAKET</th>
                        <th class="text-center">
                            {{ $orders->sum('totalProduct') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $orders->links('cms.layouts.paginate') }}
            </div>
        @endif
        <!-- END: Pagination -->
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('records_per_page').addEventListener('change', function() {
                const perPage = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('perPage', perPage);
                window.location.search = urlParams.toString();
            });
        });
    </script>
@endpush
