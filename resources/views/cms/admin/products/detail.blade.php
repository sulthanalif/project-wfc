@extends('cms.layouts.app', [
    'title' => 'Detail Barang',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Barang
        </h2>
        <a href="{{ route('product.index') }}" class="btn btn-primary w-24 mr-1">Kembali</a>
    </div>

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center">
                @if ($product->detail->image == 'image.jpg')
                    <img alt="PAKET SMART WFC" class=" img-fluid rounded-md" src="{{ asset('assets/logo2.PNG') }}">
                @else
                    <img alt="PAKET SMART WFC" class=" img-fluid rounded-md"
                        src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}">
                @endif
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">{{ $product->name }}</h1>
                        <span class="text-muted flex flex-row items-center">
                            @if ($product->created_at == $product->updated_at)
                                <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Dibuat
                                {{ \Carbon\Carbon::parse($product->created_at)->format('d M Y, H:m:i') }}
                            @else
                                <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Diupdate
                                {{ \Carbon\Carbon::parse($product->updated_at)->format('d M Y, H:m:i') }}
                            @endif
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-0 mt-2">
                        <div class="col-span-4 lg:col-span-3 intro-y">
                            <p>Supplier</p>
                            <p>Paket</p>
                            <p>Harga</p>
                            <p>Jangka Waktu</p>
                            <p>Satuan</p>
                            <p>Deskripsi</p>
                        </div>
                        <div class="col-span-1 intro-y">
                            <p>:</p>
                            <p>:</p>
                            <p>:</p>
                            <p>:</p>
                            <p>:</p>
                            <p>:</p>
                        </div>
                        <div class="col-span-6 lg:col-span-10 intro-y">
                            <p>{{ $product->supplierName->name }}</p>
                            <p>{{ $product->packageName->name }}</p>
                            <p>Rp. {{ number_format($product->price, 0, ',', '.') }}/hari</p>
                            <p>{{ $product->days }} hari</p>
                            <p>{{ $product->unit }}</p>
                            <p>{!! $product->detail->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
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
                                                Sub Barang Pada Product Ini!</td>
                                        </tr>
                                    @else
                                        @foreach ($subProducts as $sub)
                                            <tr class="intro-x">
                                                <td>
                                                    <p class="font-medium whitespace-nowrap text-center">
                                                        {{ $loop->iteration }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-slate-500 flex items-center mr-3">{{ $sub->name }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-slate-500 flex items-center mr-3">{{ $sub->amount }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-slate-500 flex items-center mr-3">{{ $sub->unit }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-slate-500 flex items-center mr-3">Rp.
                                                        {{ number_format($sub->price, 0, ',', '.') }} </p>
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
                                            <div id="delete-confirmation-modal{{ $sub->id }}" class="modal"
                                                tabindex="-1" aria-hidden="true">
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
                                                                    action="{{ route('subProduct.destroy', [$product, $sub]) }}"
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
                        <form action="{{ route('subProduct.store', $product) }}" method="post">
                            @csrf
                            <div>
                                <label for="name" class="form-label">Nama Barang <span
                                        class="text-danger">*</span></label>
                                <input id="name" name="name" type="text" class="form-control w-full"
                                    placeholder="Masukkan Nama Barang" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                            <div class="mt-2">
                                <label for="unit" class="form-label">Satuan Barang <span
                                        class="text-danger">*</span></label>
                                <input id="unit" name="unit" type="text" class="form-control w-full"
                                    placeholder="Masukkan Satuan Barang" required>
                                @error('unit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <label for="price" class="form-label">Harga Barang <span
                                        class="text-danger">*</span></label>
                                <input id="price" name="price" type="number" class="form-control w-full"
                                    placeholder="Masukkan Harga Barang" required>
                                @error('price')
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
@endsection
