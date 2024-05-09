<table class="table table-striped">
    <thead>
        <tr>
            <th class="text-center whitespace-nowrap">#</th>
            <th class="whitespace-nowrap">TANGGAL</th>
            <th class="whitespace-nowrap">JUMLAH BAYAR</th>
            {{-- <th class="whitespace-nowrap">SISA PEMBAYARAN</th> --}}
            {{-- <th class="whitespace-nowrap">BUKTI PEMBAYARAN</th> --}}
            <th class="whitespace-nowrap">STATUS</th>
            {{-- <th class="text-center whitespace-nowrap">AKSI</th> --}}
        </tr>
    </thead>
    <tbody>
        @if (!$order->payment)
            <tr>
                <td colspan="6" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
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

                    {{-- <td>
                        @if ($payment->image !== NULL)
                            <div class="flex">
                                <div class="w-10 h-10 image-fit zoom-in">
                                    <img alt="PAKET SMART WFC" class="tooltip rounded-full"
                                        src="{{ route('getImage', ['path' => 'payment/'.$order->agent_id, 'imageName' => $payment->image]) }}"
                                        title="@if ($payment->created_at == $payment->updated_at) Diupload {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:m:i') }}
                                        @else
                                        Diupdate {{ \Carbon\Carbon::parse($payment->updated_at)->format('d M Y, H:m:i') }} @endif">
                                </div>
                            </div>
                        @else
                        <a class="flex items-center text-success" href="javascript:;" data-tw-toggle="modal"
                        data-tw-target="#upload-confirmation-modal{{ $payment->id }}">
                         <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Upload bukti pembayaran </a>
                        @endif
                    </td> --}}

                    <td>
                        @if ($payment->status === 'success')
                            <div class="flex items-center justify-center text-success"> <i
                                    data-lucide="check-square" class="w-4 h-4 mr-2"></i> </div>
                        @else
                            <div class="flex items-center justify-center text-danger"> <i data-lucide="x-square"
                                    class="w-4 h-4 mr-2"></i></div>
                        @endif
                    </td>

                        {{-- <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#change-confirmation-modal{{ $order->id }}">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                <a class="flex items-center text-success" href="javascript:;" data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal{{ $order->id }}">
                                     <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Accept </a>
                            </div>
                        </td> --}}
                </tr>

                <!-- BEGIN: Delete Confirmation Modal -->
                    <div id="upload-confirmation-modal{{$payment->id}}" class="modal" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="p-5 text-center">
                                    {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                                <form action="{{ route('storePaymentImage', ['order' => $order, 'payment' => $payment]) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mt-3">
                                        <label for="image" class="form-label">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                                        <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                            <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                            <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                            <input id="image" name="image" type="file"
                                                class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                                        </div>
                                        <div id="image-preview" class="hidden mt-2"></div>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="px-5 pb-8 text-center">


                                            <button type="submit" class="btn btn-success w-24">Simpan</button>
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                            </div>
                                        </div>
                                </form>
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
                <th colspan="2" align="center">SISA PEMBAYARAN</th>
                <th>
                    Rp. {{ number_format($order->payment->sortByDesc('created_at')->first()->remaining_payment, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    @endif
</table>
