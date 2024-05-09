@extends('cms.layouts.app', [
    'title' => 'Detail Distribusi',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Distribusi
        </h2>
    </div>

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">{{ $distribution->distribution_number }}</h1>
                        <span class="text-muted">Nomer Order : {!! $distribution->order->order_number !!}</span>
                    </div>

                    <div class="flex items-center mt-2">
                        Tanggal : {{$distribution->date}}
                    </div>
                    <div class="flex items-center mt-2">
                        Driver : {{$distribution->driver}}
                    </div>
                    <div class="flex items-center mt-2">
                        Nomer Polisi : {{$distribution->police_number}}
                    </div>
                </div>
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <span class="text-muted">Detail Order</span>
                    </div>

                    {{-- table --}}
                    @include('cms.admin.distributions.table-order')
                </div>
                <div class="mt-5">
                    <a href="#" class="btn btn-primary">Cetak Surat Jalan</a>
                    <a href="{{ route('distribution.index') }}" class="btn btn-danger">kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
