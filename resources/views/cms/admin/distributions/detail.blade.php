@extends('cms.layouts.app', [
    'title' => 'Detail Distribusi',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Distribusi
        </h2>
        <div class="flex">
            <div class="dropdown mr-2">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i> </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            <form action="{{ route('export.printed', ['distribution' => $distribution]) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center dropdown-item">
                                    <i data-lucide="file" class="w-4 h-4 mr-2"></i>
                                    Cetak
                                    Surat
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <a href="{{ route('distribution.index') }}" class="btn btn-primary w-24 mr-1">Kembali</a>
        </div>
    </div>

    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
        <div class="col-span-12">
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 lg:col-span-4">
                    <div class="box p-5 rounded-md">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <div class="font-medium text-base truncate">Detail @if ($distribution->is_delivery)
                                    Pengiriman
                                @else
                                    Pengambilan
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Nomor
                            : <span class="underline decoration-dotted ml-1">{{ $distribution->distribution_number }}</span>
                        </div>
                        <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Waktu : {{ \Carbon\Carbon::parse($distribution->date)->format('d M Y') }} </div>
                        @if ($distribution->is_delivery)
                            <div class="flex items-center mt-3"> <i data-lucide="clipboard"
                                    class="w-4 h-4 text-slate-500 mr-2"></i> Nomor
                                Polisi: {{ $distribution->police_number }} </div>
                        @endif
                        <div class="flex items-center mt-3"> <i data-lucide="user" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Oleh: {{ $distribution->driver }}
                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-4">
                    <div class="box p-5 rounded-md">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <div class="font-medium text-base truncate">Detail Pesanan</div>
                        </div>
                        <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Nomor
                            Pesanan: <span
                                class="underline decoration-dotted ml-1">{{ $distribution->order->order_number }}</span>
                        </div>
                        <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Waktu Pesanan: {{ \Carbon\Carbon::parse($distribution->order->order_date)->format('d M Y') }}
                        </div>
                        <div class="flex items-center mt-3"> <i data-lucide="pie-chart"
                                class="w-4 h-4 text-slate-500 mr-2"></i> Status
                            Pesanan:
                            @if ($distribution->order->status === 'accepted')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Diterima</span>
                            @elseif ($distribution->order->status === 'stop')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Mundur</span>
                            @elseif ($distribution->order->status === 'reject')
                                <span class="bg-danger text-white rounded px-2 ml-1">Ditolak</span>
                            @elseif ($distribution->order->status === 'canceled')
                                <span class="bg-danger text-white rounded px-2 ml-1">Dibatalkan</span>
                            @else
                                <span class="bg-warning/20 text-warning rounded px-2 ml-1">Pending</span>
                            @endif
                        </div>

                        @if ($distribution->order->status === 'reject')
                            <div class="flex items-center mt-3"> <i data-lucide="file-text"
                                    class="w-4 h-4 text-slate-500 mr-2"></i>
                                Keterangan:
                                <p class="ml-1">{{ $order->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($distribution->is_delivery)
                    <div class="col-span-12 lg:col-span-4">
                        <div class="box p-5 rounded-md">
                            <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                <div class="font-medium text-base truncate">Detail Penerima</div>
                            </div>
                            @php
                                $details = $distribution->detail;
                                $data = [];
                                $tampilkan = [];
                                foreach ($details as $d) {
                                    if (!$d->orderDetail->sub_agent_id) {
                                        $query = $d->orderDetail->order->agent->agentProfile;
                                        $data[] = [
                                            'name' => $query->name,
                                            'phone_number' => $query->phone_number ?? 'Nomer HP Belum Diisi',
                                            'address' => $query->address
                                                ? "{$query->address} RT {$query->rt} / RW {$query->rw}, {$query->village}, {$query->district}, {$query->regency}, {$query->province}"
                                                : 'Alamat Belum Diisi',
                                        ];
                                    } else {
                                        $data[] = $d->orderDetail->subAgent->agentProfile;
                                    }
                                }

                                foreach (array_filter($data) as $d) {
                                    $tampilkan = $d;
                                }

                                if ($tampilkan == null) {
                                    $tampilkan = [
                                        'name' => $details->first()->orderDetail->subAgent->name,
                                        'phone_number' => $details->first()->orderDetail->subAgent->phone_number,
                                        'address' => $details->first()->orderDetail->subAgent->address,
                                    ];
                                }

                            @endphp
                            <div class="flex items-center"> <i data-lucide="clipboard"
                                    class="w-4 h-4 text-slate-500 mr-2"></i>
                                Penerima:
                                <span class="underline decoration-dotted ml-1">{{ $tampilkan['name'] }}</span>
                            </div>
                            <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                    class="w-4 h-4 text-slate-500 mr-2"></i>
                                Nomor Telepon: {{ $tampilkan['phone_number'] }} </div>
                            <div class="flex items-center mt-3"> <i data-lucide="map-pin"
                                    class="w-4 h-4 text-slate-500 mr-2"></i>
                                Alamat: {!! $tampilkan['address'] !!} </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-span-12">
            <div class="box p-5 rounded-md">
                <div class="d-flex justify-content-between items-center border-bottom pb-3 mb-3">
                    <div class="font-medium text-base truncate">Detail Produk Distribusi</div>
                    {{-- <div class="font-medium text-base truncate">Total Uang: {{ number_format($total_money, 0, ',', '.') }}</div> --}}
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap text-center">Penerima</th>
                                <th class="whitespace-nowrap text-center">Produk</th>
                                {{-- <th class="whitespace-nowrap text-center">Harga per Item</th> --}}
                                <th class="whitespace-nowrap text-center">Qty</th>
                                @hasrole('admin|super_admin|finance_admin')
                                <th class="whitespace-nowrap text-center">Jumlah Uang</th>
                                @endhasrole
                                {{-- <th class="whitespace-nowrap text-center">Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_rupiah = 0;
                            @endphp
                            @foreach ($distribution->detail as $item)
                                <tr>
                                    <td>
                                        {{ $item->orderDetail->sub_agent_id ? $item->orderDetail->subAgent->name : $distribution->order->agent->agentProfile->name }}
                                    </td>
                                    <td class="!py-4">
                                        <div class="flex items-center justify-center">
                                            @hasrole('admin|super_admin|finance_admin')
                                                @if ($item->orderDetail->product->detail->image == null)
                                                    -
                                                @else
                                                    @if ($item->orderDetail->product->detail->image == 'image.jpg')
                                                        <div class="w-10 h-10 image-fit zoom-in">
                                                            <img alt="PAKET SMART WFC"
                                                                class="rounded-lg border-2 border-white shadow-md"
                                                                src="{{ asset('assets/logo2.png') }}">
                                                        </div>
                                                    @else
                                                        <div class="w-10 h-10 image-fit zoom-in">
                                                            <img alt="PAKET SMART WFC"
                                                                class="rounded-lg border-2 border-white shadow-md"
                                                                src="{{ route('getImage', ['path' => 'product', 'imageName' => $item->orderDetail->product->detail->image]) }}">
                                                        </div>
                                                    @endif
                                                @endif
                                                <a href="{{ route('product.show', $item->orderDetail->product_id) }}"
                                                    class="font-medium whitespace-nowrap ml-4">{{ $item->orderDetail->product->name }}
                                                    {{ $item->orderDetail->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</a>
                                            @endhasrole
                                            @hasrole('agent')
                                                @if ($item->product->detail->image == null)
                                                    -
                                                @else
                                                    @if ($item->product->detail->image == 'image.jpg')
                                                        <div class="w-10 h-10 image-fit zoom-in">
                                                            <img alt="PAKET SMART WFC"
                                                                class="rounded-lg border-2 border-white shadow-md"
                                                                src="{{ asset('assets/logo2.png') }}">
                                                        </div>
                                                    @else
                                                        <div class="w-10 h-10 image-fit zoom-in">
                                                            <img alt="PAKET SMART WFC"
                                                                class="rounded-lg border-2 border-white shadow-md"
                                                                src="{{ route('getImage', ['path' => 'product', 'imageName' => $item->orderDetail->product->detail->image]) }}">
                                                        </div>
                                                    @endif
                                                @endif
                                                <span class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}
                                                    {{ $item->orderDetail->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</span>
                                            @endhasrole
                                        </div>
                                    </td>
                                    {{-- <td class="text-center">Rp. {{ number_format($item->product->price, 0, ',', '.') }}
                                    </td> --}}
                                    <td class="text-center">{{ $item->qty }}</td>
                                    @hasrole('admin|super_admin|finance_admin')
                                    <td class="text-center">{{ number_format($item->orderDetail->product->sumRupiah() * $item->qty, 0, ',', '.') }}</td>
                                    @endhasrole
                                    {{-- <td class="text-center">Rp. {{ number_format($item->sub_price, 0, ',', '.') }}</td> --}}
                                </tr>
                                @php
                                    $total_qty += $item->qty;
                                    $total_rupiah += $item->orderDetail->product->sumRupiah() * $item->qty;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="text-center">
                                <th colspan="2">TOTAL</th>
                                <th>{{ $total_qty }}</th>
                                @hasrole('admin|super_admin|finance_admin')
                                <th>{{ number_format($total_rupiah, 0, ',', '.') }}</th>
                                @endhasrole
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
