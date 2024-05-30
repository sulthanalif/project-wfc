@extends('cms.layouts.app', [
    'title' => 'Detail Paket',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Paket
        </h2>
        <a href="{{ route('package.index') }}" class="btn btn-primary w-24 mr-1">Kembali</a>
    </div>

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                @if ($package->image == null)
                    <img alt="PAKET SMART WFC" class=" img-fluid rounded-md" src="{{ asset('assets/logo2.PNG') }}">
                @else
                    <img alt="PAKET SMART WFC" class=" img-fluid rounded-md"
                        src="{{ route('getImage', ['path' => 'package', 'imageName' => $package->image]) }}">
                @endif
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">{{ $package->name }}</h1>
                        <span class="text-muted">Katalog: {!! $package->catalogName->name !!}</span>
                    </div>
                    <div class="flex items-center justify-between mt-2 gap-3 pt-2">
                        <div class="flex items-center"> <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Dibuat
                            {{ \Carbon\Carbon::parse($package->created_at)->format('d M Y, H:m:i') }} </div>
                        <div class="flex items-center"> <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Diupdate
                            {{ \Carbon\Carbon::parse($package->updated_at)->format('d M Y, H:m:i') }} </div>
                    </div>
                    <div class="flex items-center mt-2">
                        {!! $package->description !!} </div>
                </div>
            </div>
        </div>
    </div>
@endsection
