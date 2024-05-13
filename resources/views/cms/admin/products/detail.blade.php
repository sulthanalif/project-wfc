@extends('cms.layouts.app', [
    'title' => 'Detail Barang',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Barang
        </h2>
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
                            <p>Deskripsi</p>
                        </div>
                        <div class="col-span-1 intro-y">
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
                            <p>{!! $product->detail->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
