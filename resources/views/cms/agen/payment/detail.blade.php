@extends('cms.layouts.app', [
    'title' => 'Pembayaran Paket',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Pembayaran Paket #{{ $order->order_number }}
        </h2>
        <a href="{{ route('payment-agent.index') }}" class="btn px-2 box"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
        @if ($order->payment_status !== 'paid')
            <a class="btn btn-primary btn-sm py-2 px-3 shadow-md flex items-center ml-2" href="javascript:;"
                data-tw-toggle="modal" data-tw-target="#payment-confirmation-modal">
                Setor </a>
            @include('cms.agen.payment.modal.payment')
        @endif
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">TANGGAL</th>
                        <th class="text-center whitespace-nowrap">METODE</th>
                        <th class="text-center whitespace-nowrap" width="10%">JUMLAH BAYAR</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap">KETERANGAN</th>
                        <th class="text-center whitespace-nowrap">BUKTI</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($order->payment->isEmpty())
                        <tr>
                            <td colspan="6" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($order->payment->sortByDesc('date') as $payment)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <p>
                                        @if ($payment->method == 'Transfer')
                                            {{ $payment->bank_owner_id ? $banks->where('id', $payment->bank_owner_id)->first()->name : $payment->bank }}
                                            <br />
                                            ({{ $payment->bank_owner_id ? $banks->where('id', $payment->bank_owner_id)->first()->account_number . ' - ' . $banks->where('id', $payment->bank_owner_id)->first()->account_name : 'Detail rekening belum diisi' }})
                                        @elseif ($payment->method == 'Tunai')
                                            Tunai
                                        @endif
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p>
                                        Rp. {{ number_format($payment->pay, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    @if ($payment->status == 'accepted')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Diterima </div>
                                    @elseif ($payment->status == 'rejected')
                                        <div class="flex items-center justify-center text-danger"> <i
                                                data-lucide="alert-circle" class="w-4 h-4 mr-2"></i> Ditolak </div>
                                    @else
                                        <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                                class="w-4 h-4 mr-2"></i> Pending</div>
                                    @endif
                                </td>
                                <td>
                                    <p>{!! $payment->note ?? '' !!}</p>
                                </td>
                                <td class="w-40">
                                    <div class="flex items-center justify-center">
                                        <div class="w-10 h-10 image-fit zoom-in">
                                            @if ($payment->photo == null)
                                                <img alt="PAKET SMART WFC" class="rounded-full"
                                                    src="{{ asset('assets/logo2.png') }}">
                                            @else
                                                <img alt="PAKET SMART WFC" class="tooltip rounded-full"
                                                    data-tw-toggle="modal" data-tw-target="#image-modal-{{ $payment->id }}"
                                                    src="{{ route('getImage', ['path' => 'proofs', 'imageName' => $payment->photo]) }}"
                                                    title="@if ($payment->created_at == $payment->updated_at) Diupload {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }} @else Diupdate {{ \Carbon\Carbon::parse($payment->updated_at)->format('d M Y, H:m:i') }} @endif">
                                            @endif
                                            <!-- Image Modal -->
                                            <div id="image-modal-{{ $payment->id }}" class="modal" tabindex="-1" aria-hidden="true">
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
                                <td class="table-report__action">
                                    <div class="flex justify-center items-center">
                                        @if ($payment->status == 'pending')
                                            <a class="flex items-center mr-3 text-warning" href="javascript:;"
                                                data-tw-toggle="modal"
                                                data-tw-target="#update-detail-confirmation-modal{{ $payment->id }}">
                                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit </a>
                                            @include('cms.agen.payment.modal.update-detail')
                                        @endif

                                        @if ($payment->status == 'accepted')
                                            <a class="flex items-center mr-3 text-success" target="_blank"
                                                href="{{ route('getInvoice', ['order' => $order, 'payment' => $payment]) }}">
                                                <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Cetak </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                @if ($order->payment->first())
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">SISA PEMBAYARAN</th>
                            <th class="text-center">
                                Rp.
                                {{ number_format($order->payment->sortByDesc('created_at')->first() ? $order->total_price - $order->payment()->where('status', 'accepted')->sum('pay') : $order->total_price, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        <!-- END: Data List -->
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
