<table class="table table-striped">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="text-center">TANGGAL</th>
            <th class="text-center">JUMLAH BAYAR</th>
            <th class="text-center">BUKTI</th>
            <th class="text-center">STATUS</th>
            <th class="text-center">KETERANGAN</th>
            @hasrole('finance_admin|super_admin')
                <th class="text-center ">AKSI</th>
            @endhasrole
        </tr>
    </thead>
    <tbody>
        @if ($order->payment->isEmpty())
            <tr>
                <td colspan="7" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
            </tr>
        @else
            @foreach ($order->payment as $payment)
                <tr class="intro-x">
                    <td>
                        <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }}
                    </td>
                    <td>
                        <p>
                            Rp. {{ number_format($payment->pay, 0, ',', '.') }}
                        </p>
                    </td>

                    <td class="text-center">
                        <div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in">
                                <img alt="PAKET SMART WFC" class="rounded-lg border-2 border-white shadow-md tooltip"
                                    src="{{ route('getImage', ['path' => 'payment/' . $order->agent_id, 'imageName' => $payment->image]) }}"
                                    title="@if ($payment->created_at == $payment->updated_at) Diupload {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }}
                                        @else
                                        Diupdate {{ \Carbon\Carbon::parse($payment->updated_at)->format('d M Y, H:m:i') }} @endif">
                            </div>
                        </div>
                    </td>

                    <td>
                        @if ($payment->status === 'success')
                            <div class="flex items-center justify-center text-success"> <i data-lucide="check-square"
                                    class="w-4 h-4 mr-2"></i> </div>
                        @else
                            @if ($payment->status === 'reject')
                                <div class="flex items-center justify-center text-danger"> <i data-lucide="x-square"
                                        class="w-4 h-4 mr-2"></i></div>
                            @else
                                <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                        class="w-4 h-4 mr-2"></i></div>
                            @endif
                        @endif
                    </td>

                    <td>
                        <p>
                            {{ $payment->description }}
                        </p>
                    </td>

                    @hasrole('finance_admin|super_admin')
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                @if ($payment->status === 'pending')
                                    <a class="flex items-center mr-3 text-warning" href="javascript:;"
                                        data-tw-toggle="modal"
                                        data-tw-target="#cpayment-confirmation-modal{{ $payment->id }}">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                @endif
                                {{-- <a class="flex items-center text-success" href="javascript:;" data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal{{ $order->id }}">
                                     <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Accept </a> --}}
                            </div>
                        </td>
                    @endhasrole
                </tr>

                <!-- BEGIN: Change Status Payment Confirmation Modal -->
                <div id="cpayment-confirmation-modal{{ $payment->id }}" class="modal" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="p-5 text-center">
                                    <i data-lucide="alert-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                    <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                    <div class="text-slate-500 mt-2">
                                        Apakah anda yakin untuk mengubah status pesanan ini?
                                        <br>
                                        Proses tidak akan bisa diulangi.
                                    </div>
                                </div>
                                <div class="px-5 pb-8 text-center">
                                    <form action="{{ route('changePaymentStatus', $payment) }}" method="post">
                                        @csrf
                                        {{-- @method('put') --}}
                                        <div class="mb-3">
                                            <select class="form-select mt-2 sm:mr-2" id="status" name="status"
                                                required>
                                                <option value="success"
                                                    {{ $payment->status == 'success' ? 'selected' : '' }}>Diterima
                                                </option>
                                                <option value="reject"
                                                    {{ $payment->status == 'reject' ? 'selected' : '' }}>Ditolak
                                                </option>

                                            </select>
                                        </div>

                                        <div class="mb-3" id="desc-fields" style="display: none">
                                            <textarea id="description" name="description" class="form-control w-full" placeholder="Masukkan Description "></textarea>
                                        </div>
                                        {{-- <input type="hidden" name="page"
                                                        value="{{ $payment->currentPage() }}"> --}}
                                        <button type="submit" class="btn btn-warning w-24">Ubah</button>
                                        <button type="button" data-tw-dismiss="modal"
                                            class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Change Status Payment Confirmation Modal -->
            @endforeach
        @endif
    </tbody>
    @if ($order->payment->first())
        <tfoot>
            <tr>
                <th colspan="2" align="center">SISA PEMBAYARAN</th>
                <th>
                    Rp.
                    {{ number_format($order->payment->sortByDesc('created_at')->where('status', 'success')->first() ? $order->payment->sortByDesc('created_at')->where('status', 'success')->first()->remaining_payment : $order->total_price, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    @endif
</table>

@push('custom-scripts')
    <script>
        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            const statSelect = modal.querySelector('#status');

            if (statSelect) {
                statSelect.addEventListener('change', (event) => {
                    const descField = modal.querySelector('#desc-fields');
                    if (event.target.value === 'reject') {
                        descField.style.display = 'block';
                    } else {
                        descField.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endpush
