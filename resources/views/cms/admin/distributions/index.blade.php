@extends('cms.layouts.app', [
    'title' => 'Distribusi',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Distribusi
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('distribution.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Distribusi</a>
            @include('cms.layouts.per-page')

            @if ($distributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $distributions->firstItem() }} hingga
                    {{ $distributions->lastItem() }} dari {{ $distributions->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $distributions->count() }} data
                </div>
            @endif
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..." title="Hanya mencari data pada halaman yang sedang aktif" id="filter" value="{{ request('search') }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <x-table.filter :filters="$filters" />
        <!-- BEGIN: Data List -->
        <x-table.wrapper>
            <x-slot:thead>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="whitespace-nowrap">NOMOR PENGIRIMAN</th>
                    <th class="whitespace-nowrap">TANGAL</th>
                    <th class="whitespace-nowrap">DRIVER</th>
                    <th class="whitespace-nowrap">PENERIMA</th>
                    <th class="whitespace-nowrap">NOMOR PESANAN</th>
                    <th class="whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">AKSI</th>
                </tr>
            </x-slot:thead>
            @if ($distributions->isEmpty())
                <x-table.empty colspan="8" />
            @else
                @foreach ($distributions as $distribution)
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $distributions instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($distributions->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <a class="text-slate-500 flex items-center mr-3"
                                href="{{ route('distribution.show', $distribution) }}"> <i
                                    data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                                {{ $distribution->distribution_number }} </a>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center mr-3">
                                {{ \Carbon\Carbon::parse($distribution->date)->format('d M Y') }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center mr-3"> {{ $distribution->driver }} </p>
                        </td>
                        @php
                            $details = $distribution->detail;
                            $data = [];
                            $tampilkan = [];
                            foreach ($details as $d) {
                                if (!$d->orderDetail) {
                                    continue; // skip if orderDetail is null
                                }
                                if (!$d->orderDetail->sub_agent_id) {
                                    $query = $d->orderDetail->order->agent->agentProfile;
                                    $data[] = [
                                        'name' => $query->name ?? '-',
                                        'phone_number' => $query->phone_number ?? 'Nomer HP Belum Diisi',
                                        'address' => $query?->address ? "{$query->address} RT {$query->rt} / RW {$query->rw}, {$query->village}, {$query->district}, {$query->regency}, {$query->province}" : 'Alamat Belum Diisi',
                                    ];
                                } else {
                                    $sub = $d->orderDetail->subAgent;
                                    $data[] = $sub ? [
                                        'name' => $sub->name ?? '-',
                                        'phone_number' => $sub->phone_number ?? 'Nomer HP Belum Diisi',
                                        'address' => $sub->address ?? 'Alamat Belum Diisi',
                                    ] : null;
                                }
                            }

                            foreach (array_filter($data) as $d) {
                                $tampilkan = $d;
                            }

                            if ($tampilkan == null && $details->isNotEmpty() && $details->first()->orderDetail) {
                                $firstSubAgent = $details->first()->orderDetail->subAgent;
                                $tampilkan = [
                                    'name' => $firstSubAgent->name ?? '-',
                                    'phone_number' => $firstSubAgent->phone_number ?? 'Nomer HP Belum Diisi',
                                    'address' => $firstSubAgent->address ?? 'Alamat Belum Diisi',
                                ];
                            }
                        @endphp
                        <td>
                            <p class="text-slate-500 flex items-center mr-3"> {{ $tampilkan['name'] ?? '-' }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center mr-3">
                                {{ $distribution->order->order_number ?? '-' }} </p>
                        </td>
                        <td>
                            @if ($distribution->is_delivery)
                                @switch($distribution->status)
                                    @case('on_process')
                                        <x-badge.status status="shipping" text="Process" />
                                    @break
                                    @case('canceled')
                                        <x-badge.status status="reject" text="Canceled" />
                                    @break
                                    @default
                                        <x-badge.status status="delivered" text="Diantarkan" />
                                @endswitch
                            @else
                                <x-badge.status status="delivered" text="Diambil" />
                            @endif
                        </td>

                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                @if($distribution->is_delivery && $distribution->status != 'delivered')
                                <a class="flex items-center text-success mr-3" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#approve-confirmation-modal{{ $distribution->id }}"> <i
                                    data-lucide="check" class="w-4 h-4 mr-1"></i> Approve </a>
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal{{ $distribution->id }}"> <i
                                        data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- BEGIN: Delete Confirmation Modal -->
                    <div id="delete-confirmation-modal{{ $distribution->id }}" class="modal" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="p-5 text-center">
                                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                        <div class="text-slate-500 mt-2">
                                            Apakah anda yakin untuk menghapus data ini?
                                            <br>
                                            Proses tidak akan bisa diulangi.
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <form action="{{ route('distribution.destroy', $distribution) }}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            @if ($distributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                <input type="hidden" name="page"
                                                    value="{{ $distributions->currentPage() }}">
                                            @endif
                                            <button type="submit" class="btn btn-danger w-24">Hapus</button>
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Delete Confirmation Modal -->
                    <div id="approve-confirmation-modal{{ $distribution->id }}" class="modal" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="p-5 text-center">
                                        <i data-lucide="x-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                        <div class="text-slate-500 mt-2">
                                            Apakah anda yakin untuk approve data ini?
                                            <br>
                                            Proses tidak akan bisa diulangi.
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <form action="{{ route('distribution.approve', $distribution) }}"
                                            method="post">
                                            @csrf
                                            @method('PUT')
                                            @if ($distributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                <input type="hidden" name="page"
                                                    value="{{ $distributions->currentPage() }}">
                                            @endif
                                            <button type="submit" class="btn btn-success text-white w-24">Approve</button>
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Delete Confirmation Modal -->
                @endforeach
            @endif
        </x-table.wrapper>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-table.pagination :collection="$distributions" />
        <!-- END: Pagination -->
    </div>
@endsection
