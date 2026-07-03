@extends('cms.layouts.app', [
    'title' => 'Detail Arsip Komisi',
])

@section('content')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Arsip Komisi
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="{{ route('commissions.archive') }}" class="btn btn-outline-secondary shadow-md"> <i data-lucide="arrow-left"
                    class="w-4 h-4 mr-2"></i> Kembali</a>
        </div>
    </div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">{{ $commission->title }}</div>
                </div>
                <div class="flex items-center mb-2">
                    <i data-lucide="clipboard-check" class="w-4 h-4 mr-2 text-slate-500"></i>
                    <span class="font-medium mr-2">Paket:</span>
                    {{ $commission->package->name }}
                </div>
                <div class="flex items-center mb-2">
                    <i data-lucide="target" class="w-4 h-4 mr-2 text-slate-500"></i>
                    <span class="font-medium mr-2">Target:</span>
                    {{ $commission->term }}
                </div>
                <div class="flex items-center">
                    <i data-lucide="award" class="w-4 h-4 mr-2 text-slate-500"></i>
                    <span class="font-medium mr-2">Bonus:</span>
                    {{ $commission->reward }}
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 rounded-md">
                <div>
                    <h3 class="font-medium text-base mb-2">Deskripsi</h3>
                    <div class="text-slate-600 dark:text-slate-500 leading-relaxed">
                        {!! $commission->description !!}
                    </div>
                </div>
            </div>
            <div class="box p-5 rounded-md mt-5">
                <div>
                    <h3 class="font-medium text-base mb-2">Daftar Pemenang KOMISI</h3>
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="text-center whitespace-nowrap">#</th>
                                <th class="text-center whitespace-nowrap">NAMA</th>
                                <th class="text-center whitespace-nowrap">TOTAL PRODUK</th>
                                <th class="text-center whitespace-nowrap">TOTAL BONUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($winners))
                                <tr>
                                    <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Pemenang</td>
                                </tr>
                            @else
                                @foreach ($winners as $winner)
                                    <tr class="intro-x">
                                        <td>
                                            <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                        </td>
                                        <td>
                                            <p class="text-slate-500 text-center">{{ $winner['name'] }}</p>
                                        </td>
                                        <td>
                                            <p class="text-slate-500 text-center">{{ $winner['total_product'] }}</p>
                                        </td>
                                        <td>
                                            <p class="text-slate-500 text-center">Rp {{ number_format($winner['total_bonus'], 0, ',', '.') }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
