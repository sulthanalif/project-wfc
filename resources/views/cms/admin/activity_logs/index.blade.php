@extends('cms.layouts.app', [
    'title' => 'Log Aktivitas',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Log Aktivitas
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @include('cms.layouts.per-page')

            @if ($logs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $logs->firstItem() }} hingga
                    {{ $logs->lastItem() }} dari {{ $logs->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $logs->count() }} data
                </div>
            @endif
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..." title="Hanya mencari data pada halaman yang sedang aktif" id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>

        <x-table.filter :filters="$filters" />

        <!-- BEGIN: Data List -->
        <x-table.wrapper class="!overflow-x-auto">
            <x-slot:header>
                <tr>
                    <th class="text-center whitespace-nowrap w-12">#</th>
                    <th class="whitespace-nowrap w-40">WAKTU</th>
                    <th class="whitespace-nowrap w-44">PENGGUNA</th>
                    <th class="text-center whitespace-nowrap w-20">METODE</th>
                    <th class="whitespace-nowrap">AKSI</th>
                    <th class="text-center whitespace-nowrap w-20">STATUS</th>
                    <th class="text-center whitespace-nowrap w-28">IP ADDRESS</th>
                    <th class="text-center whitespace-nowrap w-24">DETAIL</th>
                </tr>
            </x-slot:header>

            @if ($logs->isEmpty())
                <x-table.empty colspan="8" message="Belum Ada Log Aktivitas yang Tercatat" />
            @else
                @foreach ($logs as $log)
                    <tr class="intro-x">
                        <td class="text-center font-medium w-12">
                            {{ $logs instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($logs->firstItem() + $loop->index) : $loop->iteration }}
                        </td>
                        <td class="whitespace-nowrap w-40">
                            <div class="font-medium text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-slate-500 text-xs mt-0.5">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="whitespace-nowrap w-44">
                            <div class="font-medium text-sm">{{ $log->user_name ?? 'Guest' }}</div>
                            <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-[10px] font-semibold
                                {{ $log->user_role === 'super_admin' ? 'bg-danger/20 text-danger' :
                                   ($log->user_role === 'admin' ? 'bg-primary/20 text-primary' :
                                   ($log->user_role === 'finance_admin' ? 'bg-warning/20 text-warning' :
                                   ($log->user_role === 'agent' ? 'bg-success/20 text-success' : 'bg-slate-200 text-slate-600'))) }}">
                                {{ $log->user_role ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap w-20">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                {{ $log->method === 'POST' ? 'bg-primary text-white' :
                                   ($log->method === 'PUT' || $log->method === 'PATCH' ? 'bg-warning text-white' :
                                   ($log->method === 'DELETE' ? 'bg-danger text-white' : 'bg-slate-200 text-slate-700')) }}">
                                {{ $log->method }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap">
                            <span class="font-medium text-sm" title="{{ $log->action }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap w-20">
                            @if ($log->status_code)
                                <span class="px-2 py-0.5 rounded text-xs font-semibold
                                    {{ $log->status_code >= 200 && $log->status_code < 300 ? 'text-success bg-success/10' :
                                       ($log->status_code >= 300 && $log->status_code < 400 ? 'text-primary bg-primary/10' :
                                       'text-danger bg-danger/10') }}">
                                    {{ $log->status_code }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap text-xs text-slate-600 w-28 font-mono">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="table-report__action text-center whitespace-nowrap w-24">
                            <a class="btn btn-sm btn-outline-primary px-2 py-1" href="javascript:;"
                                data-tw-toggle="modal" data-tw-target="#detail-log-modal-{{ $log->id }}">
                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                            </a>

                            <!-- BEGIN: Detail Modal -->
                            <div id="detail-log-modal-{{ $log->id }}" class="modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content text-left">
                                        <div class="modal-header">
                                            <h2 class="font-medium text-base mr-auto">Detail Log Aktivitas #{{ $log->id }}</h2>
                                            <span class="text-slate-500 text-xs">{{ $log->created_at->format('d M Y H:i:s') }}</span>
                                        </div>
                                        <div class="modal-body p-5 space-y-4">
                                            <div class="grid grid-cols-2 gap-4 pb-3 border-b border-slate-200">
                                                <div>
                                                    <span class="text-slate-500 text-xs block">Pengguna</span>
                                                    <span class="font-medium text-sm">{{ $log->user_name ?? 'Guest' }} ({{ $log->user_role ?? '-' }})</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500 text-xs block">IP Address & Status</span>
                                                    <span class="font-medium text-sm">{{ $log->ip_address ?? '-' }} | HTTP {{ $log->status_code ?? '-' }}</span>
                                                </div>
                                                <div class="col-span-2">
                                                    <span class="text-slate-500 text-xs block">URL Lengkap</span>
                                                    <code class="text-xs bg-slate-100 p-1 rounded block break-all">{{ $log->url }}</code>
                                                </div>
                                                <div class="col-span-2">
                                                    <span class="text-slate-500 text-xs block">User Agent</span>
                                                    <span class="text-xs text-slate-600 break-all">{{ $log->user_agent ?? '-' }}</span>
                                                </div>
                                            </div>

                                            <div>
                                                <span class="text-slate-500 text-xs block mb-1">Payload / Parameter Input (Tersaring)</span>
                                                @if (!empty($log->payload))
                                                    <pre class="bg-slate-900 text-emerald-400 p-3 rounded text-xs overflow-auto max-h-64 font-mono">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                @else
                                                    <p class="text-slate-400 italic text-xs">Tidak ada payload data input pada request ini.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer text-right">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Detail Modal -->
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-table.wrapper>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <x-table.pagination :collection="$logs" />
        <!-- END: Pagination -->
    </div>
@endsection
