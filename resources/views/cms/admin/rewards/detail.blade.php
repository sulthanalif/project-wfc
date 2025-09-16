@extends('cms.layouts.app', [
    'title' => 'Detail Reward',
])

@section('content')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Reward
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="{{ route('rewards.edit', $reward->id) }}" class="btn btn-primary shadow-md mr-2"> <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> Edit</a>
            <a href="{{ route('rewards.index') }}" class="btn btn-outline-secondary shadow-md"> <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali</a>
        </div>
    </div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 rounded-md">
                    <div class="mb-5">
                    <div class="h-64 image-fit rounded-md overflow-hidden">
                              @php
                              $imageUrl = ($reward->image == 'image.jpg' || $reward->image == null)
                                        ? asset('assets/logo2.png')
                                        : route('getImage', ['path' => 'reward', 'imageName' => $reward->image]);
                              @endphp
                              <img alt="{{ $reward->title }}" class="rounded-md" src="{{ $imageUrl }}">
                    </div>
                    </div>
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">{{ $reward->title }}</div>
                </div>
                <div class="flex items-center mb-2">
                    <i data-lucide="clipboard-check" class="w-4 h-4 mr-2 text-slate-500"></i>
                    <span class="font-medium mr-2">Status:</span>
                    @php
                        // Menggunakan status dari relasi Period yang sudah kita buat di percakapan sebelumnya
                        $status = $reward->period->status; 
                    @endphp
                    @if ($status === 'active')
                        <span class="bg-success/20 text-success rounded px-2 py-1 text-xs">Aktif</span>
                    @elseif ($status === 'upcoming')
                        <span class="bg-warning/20 text-warning rounded px-2 py-1 text-xs">Akan Datang</span>
                    @else
                        <span class="bg-danger/20 text-danger rounded px-2 py-1 text-xs">Berakhir</span>
                    @endif
                </div>
                <div class="flex items-center mb-2">
                    <i data-lucide="calendar-days" class="w-4 h-4 mr-2 text-slate-500"></i> 
                    <span class="font-medium mr-2">Periode:</span> 
                    
                    {{ $reward->period->description }} ( {{ Carbon\Carbon::parse($reward->period->start_date)->format('d M Y') }} - {{ Carbon\Carbon::parse($reward->period->end_date)->format('d M Y') }})
                </div>
                <div class="flex items-center">
                    <i data-lucide="target" class="w-4 h-4 mr-2 text-slate-500"></i>
                    <span class="font-medium mr-2">Target Kuantitas:</span>
                    {{ $reward->target_qty }}
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 rounded-md">
                

                <div>
                    <h3 class="font-medium text-base mb-2">Deskripsi</h3>
                    <div class="text-slate-600 dark:text-slate-500 leading-relaxed">
                        {!! $reward->description !!}
                    </div>
                </div>
            </div>
        </div>
        </div>
@endsection