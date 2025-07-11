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
            <div class="w-auto relative text-slate-500">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if ($distributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $distributions->firstItem() }} hingga
                    {{ $distributions->lastItem() }} dari {{ $distributions->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $distributions->count() }} data
                </div>
            @endif
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
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
                </thead>
                <tbody>
                    @if ($distributions->isEmpty())
                        <tr>
                            <td colspan="8" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($distributions as $distribution)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
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
                                        if (!$d->orderDetail?->sub_agent_id) {
                                            $query = $d->orderDetail?->order->agent->agentProfile;
                                            $data[] = [
                                                'name' => $query->name ?? '-',
                                                'phone_number' => $query->phone_number ?? 'Nomer HP Belum Diisi',
                                                'address' => $query?->address ? "{$query->address} RT {$query->rt} / RW {$query->rw}, {$query->village}, {$query->district}, {$query->regency}, {$query->province}" : 'Alamat Belum Diisi',
                                            ];
                                        } else {
                                            $data[] = $d->orderDetail?->subAgent->agentProfile;
                                        }
                                    }

                                    foreach (array_filter($data) as $d) {
                                        $tampilkan = $d;
                                    }

                                    if ($tampilkan == null) {
                                        $tampilkan = [
                                            'name' => $details->first()->orderDetail?->subAgent->name,
                                            'phone_number' => $details->first()->orderDetail?->subAgent->phone_number,
                                            'address' => $details->first()->orderDetail?->subAgent->address,
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
                                                <p class="text-warning flex items-center mr-3">Process</p>
                                            @break
                                            @case('canceled')
                                                <p class="text-danger flex items-center mr-3">Canceled</p>
                                            @break
                                            @default
                                                <p class="text-success flex items-center mr-3">Diantarkan</p>
                                        @endswitch
                                    @else
                                        <p class="text-success flex items-center mr-3">
                                            Diambil
                                        </p>
                                    @endif
                                </td>

                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        @if($distribution->is_delivery && $distribution->status != 'delivered')
                                        <a class="flex items-center text-success mr-3" href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#approve-confirmation-modal{{ $distribution->id }}"> <i
                                            data-lucide="check" class="w-4 h-4 mr-1"></i> Approve </a>
                                        @endif
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $distribution->id }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
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
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        @if ($distributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $distributions->links('cms.layouts.paginate') }}
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
