<table class="table table-striped">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center">TANGGAL</th>
            <th class="text-center">JUMLAH BAYAR</th>
            {{-- <th class="text-center">BUKTI</th>
            <th class="text-center">STATUS</th> --}}
            <th class="text-center">KETERANGAN</th>
            @hasrole('finance_admin|super_admin|admin')
                <th class="text-center ">AKSI</th>
            @endhasrole
        </tr>
    </thead>
    <tbody>
        @if ($order->payment->isEmpty())
            <tr>
                @hasrole('finance_admin|super_admin|admin')
                    <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                @endhasrole
                @hasrole('agent')
                    <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                @endhasrole
            </tr>
        @else
            @foreach ($order->payment->sortByDesc('date') as $payment)
                <tr class="intro-x">
                    <td>
                        <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                    </td>
                    <td class="text-center">
                        {{-- {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }} --}}
                        {{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}
                    </td>
                    <td class="text-center">
                        <p>
                            Rp. {{ number_format($payment->pay, 0, ',', '.') }}
                        </p>
                    </td>
                    <td>
                        <p>{!! $payment->note ?? '' !!}</p>
                    </td>
                    @hasrole('finance_admin|super_admin')
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">

                                <a class="flex items-center mr-3 text-success" target="_blank"
                                    href="{{ route('getInvoice', ['order' => $order, 'payment' => $payment]) }}">
                                    <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Cetak </a>
                                <a class="flex items-center mr-3 text-danger" href="javascript:;" data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal{{ $payment->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>

                                {{-- <a class="flex items-center text-success" href="javascript:;" data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal{{ $order->id }}">
                                     <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Accept </a> --}}
                            </div>
                        </td>
                    @endhasrole
                </tr>


                <!-- BEGIN: Delete Confirmation Modal -->
                <div id="delete-confirmation-modal{{ $payment->id }}" class="modal" tabindex="-1" aria-hidden="true">
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
                                    <form action="{{ route('payment.destroy', $payment) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger w-24">Hapus</button>
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
    @if ($order->payment->first())
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">SISA PEMBAYARAN</th>
                <th class="text-center">
                    Rp.
                    {{ number_format($order->payment->sortByDesc('created_at')->first() ? $order->total_price - $order->payment()->sum('pay') : $order->total_price, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    @endif
</table>
