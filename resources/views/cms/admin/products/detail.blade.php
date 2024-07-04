@extends('cms.layouts.app', [
    'title' => 'Detail Barang',
])

@section('content')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Barang
        </h2>
        <a href="{{ route('product.index') }}" class="btn btn-primary w-24 mr-1">Kembali</a>
    </div>

    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
            <div class="box">
                <div class="p-5">
                    <div
                        class="h-40 2xl:h-56 image-fit rounded-md overflow-hidden before:block before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-10 before:bg-gradient-to-t before:from-black before:to-black/10">
                        @if ($product->detail->image == 'image.jpg' || $product->detail->image == null)
                            <img alt="PAKET SMART WFC" class=" img-fluid rounded-md" src="{{ asset('assets/logo2.png') }}">
                        @else
                            <img alt="PAKET SMART WFC" class=" img-fluid rounded-md"
                                src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}">
                        @endif
                        <div class="absolute bottom-0 text-white px-5 pb-6 z-10">
                            <a href="javascript:;" class="block font-medium text-base product-name" data-tw-toggle="modal"
                                data-tw-target="#image-modal">{{ $product->name }}</a>
                            <span class="text-white/90 text-xs mt-3">Paket {{ $product->packageName->name }}</span>
                            <!-- Image Modal -->
                            <div id="image-modal" class="modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
                                        <div class="modal-body p-0 flex justify-center items-center">
                                            <img src="@if ($product->detail->image == 'image.jpg' || $product->detail->image == null) {{ asset('assets/logo2.png') }} @else {{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }} @endif"
                                                alt="Product Image" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-slate-600 dark:text-slate-500 mt-5">
                        <div class="flex items-center"> <i data-lucide="dollar-sign" class="w-4 h-4 mr-2"></i> Harga: Rp.
                            {{ number_format($product->price, 0, ',', '.') }}/hari </div>
                        <div class="flex items-center mt-2"> <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Jangka Waktu:
                            {{ $product->days }} hari </div>
                        <div class="flex items-center mt-2"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Deskripsi:
                            {!! $product->detail->description !!} </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-7 2xl:col-span-8">
            @include('cms.admin.products.sub-product')
        </div>
    </div>
@endsection
