<div class="box p-5 rounded-md">
    <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
        <div class="font-medium text-base truncate">Sub Barang</div>
        <a href="javascript:;" class="flex items-center ml-auto text-primary" data-tw-toggle="modal"
            data-tw-target="#create-confirmation-modal"> <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah </a>
    </div>
    <div class="overflow-auto lg:overflow-visible -mt-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="whitespace-nowrap !py-5">NAMA BARANG</th>
                    <th class="whitespace-nowrap text-center">JUMLAH</th>
                    <th class="whitespace-nowrap text-center">SATUAN</th>
                    <th class="whitespace-nowrap text-center">HARGA</th>
                    <th class="whitespace-nowrap text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @if ($subProducts->count() <= 0)
                    <tr>
                        <td colspan="5" class="font-medium whitespace-nowrap text-center">Tidak Ada
                            Sub Barang Pada Produk Ini!</td>
                    </tr>
                @else
                    @foreach ($subProducts as $sub)
                        <tr>
                            <td class="!py-4 whitespace-nowrap">{{ $sub->subProduct->name }}</td>
                            <td class="text-center">{{ $sub->amount }}</td>
                            <td class="text-center">{{ $sub->subProduct->unit }}</td>
                            <td class="text-center">Rp. {{ number_format($sub->subProduct->price, 0, ',', '.') }}</td>
                            <td class="table-report__action">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#delete-confirmation-modal{{ $sub->id }}">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    <!-- BEGIN: Delete Confirmation Modal -->
                                    <div id="delete-confirmation-modal{{ $sub->id }}" class="modal" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body p-0">
                                                    <div class="p-5 text-center">
                                                        <i data-lucide="x-circle"
                                                            class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                        <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                        <div class="text-slate-500 mt-2">
                                                            Apakah anda yakin untuk menghapus data ini?
                                                            <br>
                                                            Proses tidak akan bisa diulangi.
                                                        </div>
                                                    </div>
                                                    <div class="px-5 pb-8 text-center">
                                                        <form
                                                            action="{{ route('product.destroySub', ['product' => $product, 'productSubProduct' => $sub]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit"
                                                                class="btn btn-danger w-24">Hapus</button>
                                                            <button type="button" data-tw-dismiss="modal"
                                                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END: Delete Confirmation Modal -->
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- BEGIN: create Confirmation Modal -->
<div id="create-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
            <div class="modal-body p-0">
                <div class="p-5 text-center">

                    <div class="text-3xl mt-5">Sub Barang</div>

                </div>
                <div class="px-5 pb-8 text-start">
                    <form action="{{ route('product.addSubProduct', $product) }}" method="post">
                        @csrf
                        <div class="mt-3">
                            <label for="sub_product_id" class="form-label">Sub Barang <span
                                    class="text-danger">*</span></label>
                            <select class="form-select mt-2 sm:mr-2" id="sub_product_id" name="sub_product_id">
                                <option value="" disabled>Pilih Sub Barang...</option>
                                @foreach ($itemSubProduct as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} | {{ $item->unit }} |
                                        {{ $item->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="amount" class="form-label">Jumlah Barang <span
                                    class="text-danger">*</span></label>
                            <input id="amount" name="amount" type="number" class="form-control w-full"
                                placeholder="Masukkan Jumlah Barang" required>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-24">Simpan</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Confirmation Modal -->
