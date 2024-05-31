<div
    class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
    <div class="text-slate-600 dark:text-slate-500">
        <div class="flex flex-col items-center justify-center border-b pb-2">
            <h1 class="font-bold text-xl">Sub Barang</h1>
            <span class="text-muted flex flex-row items-center">
                <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#create-confirmation-modal"
                    class="btn-sm btn-primary">Tambah</a>
            </span>
            <div class="mt-3">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="text-center whitespace-nowrap">#</th>
                            <th class="whitespace-nowrap">NAMA BARANG</th>
                            <th class="whitespace-nowrap">JUMLAH</th>
                            <th class="whitespace-nowrap">SATUAN</th>
                            <th class="whitespace-nowrap">HARGA</th>
                            <th class="text-center whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($subProducts->count() <= 0)
                            <tr>
                                <td colspan="6" class="font-medium whitespace-nowrap text-center">Tidak Ada
                                    Sub Barang Pada Produk Ini!</td>
                            </tr>
                        @else
                            @foreach ($subProducts as $sub)
                                <tr class="intro-x">
                                    <td>
                                        <p class="font-medium whitespace-nowrap text-center">
                                            {{ $loop->iteration }}</p>
                                    </td>
                                    <td>
                                        <p class="text-slate-500 flex items-center mr-3">{{ $sub->subProduct->name }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-slate-500 flex items-center mr-3">{{ $sub->amount }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-slate-500 flex items-center mr-3">{{ $sub->subProduct->unit }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-slate-500 flex items-center mr-3">Rp.
                                            {{ number_format($sub->subProduct->price, 0, ',', '.') }} </p>
                                    </td>

                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center text-danger" href="javascript:;"
                                                data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal{{ $sub->id }}">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                        </div>
                                    </td>
                                </tr>


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
                                                    <form action="{{ route('product.destroySub', ['product' => $product, 'productSubProduct'=> $sub]) }}"
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
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

 <!-- BEGIN: create Confirmation Modal -->
 <div id="create-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">

                    <div class="text-3xl mt-5">Sub Barang</div>

                </div>
                <div class="px-5 pb-8 text-start">
                    <form action="{{ route('product.addSubProduct', $product) }}" method="post">
                        @csrf
                        <div class="mt-3">
                            <label for="sub_product_id" class="form-label">Sub Product <span class="text-danger">*</span></label>
                            <select class="form-select mt-2 sm:mr-2" id="sub_product_id" name="sub_product_id">
                                <option value="">Pilih...</option>
                                @foreach ($itemSubProduct as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} | {{ $item->unit }} | {{ $item->price }}</option>
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
