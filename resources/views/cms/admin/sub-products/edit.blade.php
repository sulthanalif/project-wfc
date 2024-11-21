@extends('cms.layouts.app', [
    'title' => 'Edit Sub Barang',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Sub Barang
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('sub-product.update', $subProduct) }}" method="post">
                    @method('put')
                    @csrf
                    <div>
                        <label for="name" class="form-label">Nama Sub Barang <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" class="form-control w-full"
                            placeholder="Masukkan Nama Sub Barang" required value="{{ $subProduct->name }}">
                    </div>
                    <div class="mt-3">
                        <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select class="tom-select w-full" name="unit" id="unit" required>
                            <option selected disabled>Pilih Satuan</option>
                            <option value="Rupiah" {{ isset($subProduct->unit) && $subProduct->unit === 'Rupiah' ? 'selected' : '' }}>Rupiah</option>
                            <option value="Pcs" {{ isset($subProduct->unit) && $subProduct->unit === 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Kg" {{ isset($subProduct->unit) && $subProduct->unit === 'Kg' ? 'selected' : '' }}>Kg</option>
                            <option value="Gram" {{ isset($subProduct->unit) && $subProduct->unit === 'Gram' ? 'selected' : '' }}>Gram</option>
                            <option value="Liter" {{ isset($subProduct->unit) && $subProduct->unit === 'Liter' ? 'selected' : '' }}>Liter</option>
                            <option value="Box" {{ isset($subProduct->unit) && $subProduct->unit === 'Box' ? 'selected' : '' }}>Box</option>
                            <option value="Toples" {{ isset($subProduct->unit) && $subProduct->unit === 'Toples' ? 'selected' : '' }}>Toples</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                        <input id="price" name="price" type="number" class="form-control w-full"
                            placeholder="Masukkan Harga Sub Produk" required value="{{ number_format($subProduct->price, 0, ',', '') }}">
                    </div>
                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection
