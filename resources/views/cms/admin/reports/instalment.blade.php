@extends('cms.layouts.app', [
    'title' => 'Laporan Rincian Cicilan',
])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Laporan Rincian Cicilan -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Laporan Rincian Cicilan
                        </h2>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="check" class="report-box__icon text-success"></i>
                                    </div>
                                    <div class="text-2xl font-bold leading-8 mt-6">Rp.
                                        {{ number_format($stats['pay'], 0, ',', '.') }}</div>
                                    <div class="text-base text-slate-500 mt-1">Total Terbayar</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="clock" class="report-box__icon text-warning"></i>
                                    </div>
                                    <div class="text-2xl font-bold leading-8 mt-6">Rp.
                                        {{ number_format($stats['remaining_pay'], 0, ',', '.') }}</div>
                                    <div class="text-base text-slate-500 mt-1">Total Belum Terbayar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Laporan Rincian Cicilan -->
            </div>
        </div>
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <form action="{{ route('instalment') }}" class="w-full sm:w-auto flex items-center gap-2">
                <div class="w-auto relative text-slate-500 border rounded">
                    <select id="records_select" name="agent">
                        <option value="">Semua</option>
                        @foreach ($agentsName as $agent)
                            <option value="{{ $agent }}" {{ request()->get('agent') == $agent ? 'selected' : '' }}>
                                {{ $agent }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="">
                    <input id="order_date" name="date" type="date" value="{{ request()->get('date') }}"
                        class="form-control w-full">
                </div>
                <div class="w-auto relative text-slate-500 border rounded">
                    <select id="verify" name="verify">
                        <option value="">Semua</option>
                        <option value="1" {{ request()->get('verify') == '1' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="0" {{ request()->get('verify') == '0' ? 'selected' : '' }}>Belum Terverifikasi</option>
                    </select>
                </div>
                <button class="btn btn-primary" type="submit"> <i data-lucide="search" class="w-4 h-4 mr-3"></i>
                    Cari</button>
            </form>
            <a href="{{ route('instalment', array_merge(request()->except('page'), ['export' => 1])) }}"
                class="btn btn-primary shadow-md mr-2"> <i data-lucide="file" class="w-4 h-4 mr-3"></i> Export </a>
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0 ml-auto">
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
                        <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                        <th class="text-center whitespace-nowrap">NOMER ORDER</th>
                        <th class="text-center whitespace-nowrap">JUMLAH</th>
                        <th class="text-center whitespace-nowrap">TERVERIFIKASI</th>
                        <th class="text-center whitespace-nowrap">BUKTI</th>
                        <th class="text-center whitespace-nowrap">WAKTU</th>
                        @role('super_admin|finance_admin')
                        <th class="text-center whitespace-nowrap">AKSI</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @if (!$payments)
                        <tr>
                            <td colspan="8" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($payments as $payment)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>

                                <td>
                                    <p class="text-center mr-3">
                                        {{ $payment->order->agent->agentProfile->name }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center mr-3"> {{ $payment->order->order_number }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center justify-center"> Rp.
                                        {{ number_format($payment->pay, 0, ',', '.') }} </p>
                                </td>
                                <td class="text-center">
                                    @if ($payment->is_confirmed)
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-circle" class="w-4 h-4 mr-2"></i> </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-circle"
                                                class="w-4 h-4 mr-2"></i> </div>
                                    @endif
                                </td>
                                <td class="w-40">
                                    <div class="flex items-center justify-center">
                                        <div class="w-10 h-10 image-fit zoom-in">
                                            @if ($payment->photo == null)
                                                <img alt="PAKET SMART WFC" class="rounded-full"
                                                    src="{{ asset('assets/logo2.png') }}">
                                            @else
                                                <img alt="PAKET SMART WFC" class="tooltip rounded-full"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#image-modal-{{ $payment->id }}"
                                                    src="{{ route('getImage', ['path' => 'proofs', 'imageName' => $payment->photo]) }}"
                                                    title="@if ($payment->created_at == $payment->updated_at) Diupload {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }} @else Diupdate {{ \Carbon\Carbon::parse($payment->updated_at)->format('d M Y, H:m:i') }} @endif">
                                            @endif
                                            <!-- Image Modal -->
                                            <div id="image-modal-{{ $payment->id }}" class="modal" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content"> <a data-tw-dismiss="modal"
                                                            href="javascript:;"> <i data-lucide="x"
                                                                class="w-8 h-8 text-slate-400"></i> </a>
                                                        <div class="modal-body p-0 flex justify-center items-center">
                                                            <img src="@if ($payment->photo == null) {{ asset('assets/logo2.png') }} @else {{ route('getImage', ['path' => 'proofs', 'imageName' => $payment->photo]) }} @endif"
                                                                alt="Proofs Image" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center items-center mr-3"> {{ $payment->created_at }}
                                    </p>
                                </td>
                                @role('super_admin|finance_admin')
                                <td class="table-report__action w-56">
                                    @if (!$payment->is_confirmed)
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#verify-modal-{{ $payment->id }}"> <i
                                                    data-lucide="pencil" class="w-4 h-4 mr-1"></i> Verifikasi </a>
                                        </div>
                                        @include('cms.admin.payment.modal.verify', ['payment' => $payment])
                                    @endif
                                </td>
                                @endrole
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        {{-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $paginationData->links('cms.layouts.paginate') }}
        </div> --}}
        <!-- END: Pagination -->
    </div>
@endsection
