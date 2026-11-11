@extends('cms.layouts.app', [
    'title' => 'Log Aktivitas',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Log Aktivitas Sistem
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Filter Form -->
        <div class="intro-y col-span-12">
            <div class="box p-5">
                <form method="GET" action="{{ route('admin.activity-logs') }}" class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-4 lg:col-span-3">
                        <label for="search" class="form-label text-xs">Pencarian</label>
                        <input id="search" name="search" type="text" class="form-control"
                            placeholder="Cari user, aksi, URL, IP..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-span-12 sm:col-span-4 lg:col-span-2">
                        <label for="method" class="form-label text-xs">Metode HTTP</label>
                        <select id="method" name="method" class="form-select">
                            <option value="">Semua Metode</option>
                            <option value="POST" {{ ($filters['method'] ?? '') === 'POST' ? 'selected' : '' }}>POST</option>
                            <option value="PUT" {{ ($filters['method'] ?? '') === 'PUT' ? 'selected' : '' }}>PUT</option>
                            <option value="PATCH" {{ ($filters['method'] ?? '') === 'PATCH' ? 'selected' : '' }}>PATCH</option>
                            <option value="DELETE" {{ ($filters['method'] ?? '') === 'DELETE' ? 'selected' : '' }}>DELETE</option>
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-4 lg:col-span-2">
                        <label for="role" class="form-label text-xs">Peran (Role)</label>
                        <select id="role" name="role" class="form-select">
                            <option value="">Semua Peran</option>
                            <option value="super_admin" {{ ($filters['role'] ?? '') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ ($filters['role'] ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="finance_admin" {{ ($filters['role'] ?? '') === 'finance_admin' ? 'selected' : '' }}>Admin Keuangan</option>
                            <option value="agent" {{ ($filters['role'] ?? '') === 'agent' ? 'selected' : '' }}>Agen</option>
                            <option value="guest" {{ ($filters['role'] ?? '') === 'guest' ? 'selected' : '' }}>Guest</option>
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                        <label for="date" class="form-label text-xs">Tanggal</label>
                        <input id="date" name="date" type="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-2 flex items-end gap-2">
                        <button type="submit" class="btn btn-primary w-full">Filter</button>
                        <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-secondary w-full">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Filter Form -->

        <!-- BEGIN: Data List -->
        <x-table.wrapper>
            <x-slot:header>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="whitespace-nowrap">WAKTU</th>
                    <th class="whitespace-nowrap">PENGGUNA</th>
                    <th class="text-center whitespace-nowrap">ROLE</th>
                    <th class="text-center whitespace-nowrap">METODE</th>
                    <th class="whitespace-nowrap">AKSI / URL</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">IP ADDRESS</th>
                    <th class="text-center whitespace-nowrap">DETAIL</th>
                </tr>
            </x-slot:header>

            @if ($logs->isEmpty())
                <x-table.empty colspan="9" message="Belum Ada Log Aktivitas yang Tercatat" />
            @else
                @foreach ($logs as $log)
                    <tr class="intro-x">
                        <td class="text-center font-medium">
                            {{ $logs->firstItem() + $loop->index }}
                        </td>
                        <td class="whitespace-nowrap">
                            <div class="font-medium">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-slate-500 text-xs">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="whitespace-nowrap font-medium">
                            {{ $log->user_name ?? 'Guest' }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $log->user_role === 'super_admin' ? 'bg-danger/20 text-danger' :
                                   ($log->user_role === 'admin' ? 'bg-primary/20 text-primary' :
                                   ($log->user_role === 'finance_admin' ? 'bg-warning/20 text-warning' :
                                   ($log->user_role === 'agent' ? 'bg-success/20 text-success' : 'bg-slate-200 text-slate-600'))) }}">
                                {{ $log->user_role ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                {{ $log->method === 'POST' ? 'bg-primary text-white' :
                                   ($log->method === 'PUT' || $log->method === 'PATCH' ? 'bg-warning text-white' :
                                   ($log->method === 'DELETE' ? 'bg-danger text-white' : 'bg-slate-200 text-slate-700')) }}">
                                {{ $log->method }}
                            </span>
                        </td>
                        <td>
                            <div class="font-medium truncate max-w-xs" title="{{ $log->action }}">
                                {{ $log->action }}
                            </div>
                            <div class="text-slate-500 text-xs truncate max-w-xs" title="{{ $log->url }}">
                                {{ $log->url }}
                            </div>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if ($log->status_code)
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $log->status_code >= 200 && $log->status_code < 300 ? 'text-success bg-success/10' :
                                       ($log->status_code >= 300 && $log->status_code < 400 ? 'text-primary bg-primary/10' :
                                       'text-danger bg-danger/10') }}">
                                    {{ $log->status_code }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap text-xs text-slate-600">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="table-report__action text-center whitespace-nowrap">
                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#detail-log-modal-{{ $log->id }}"
                                class="btn btn-sm btn-outline-secondary">
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

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-between">
            {{ $logs->links() }}
        </div>
        <!-- END: Pagination -->
    </div>
@endsection
