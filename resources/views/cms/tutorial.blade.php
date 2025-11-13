@extends('cms.layouts.app', [
    'title' => 'Tutorial',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tutorial
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Tutorial List -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 intro-y">
                <h3 class="text-lg font-medium mb-4">Daftar Tutorial</h3>
                <div class="overflow-x-auto">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">JUDUL</th>
                                <th class="text-center whitespace-nowrap">DESCRIPSI</th>
                                <th class="text-center whitespace-nowrap">LINK YOUTUBE</th>
                                <th class="text-center whitespace-nowrap">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tutorials as $tutorial)
                                <tr class="intro-x">
                                    <td>
                                        <span class="font-medium">{{ $tutorial->title }}</span>
                                    </td>
                                    <td class="text-center max-w-xs break-words">
                                        <span class="text-gray-500">{{ $tutorial->description }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ $tutorial->link }}" target="_blank" class="text-theme-1 underline">Tonton Video</a>
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center text-center">
                                            <a class=" btn btn-primary" href="javascript:;" onclick="loadPreview('{{ $tutorial->link }}')">
                                                Lihat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500">Tidak ada data tutorial.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Video Preview -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 intro-y">
                <h3 class="text-lg font-medium mb-4">Pratinjau Video</h3>
                <div id="preview-container" class="hidden">
                    <div class="aspect-w-16 aspect-h-9 rounded-md overflow-hidden">
                        <iframe 
                            id="preview-iframe"
                            class="w-full h-64" 
                            src=""
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <p class="text-gray-600 mt-3 text-sm">Klik tombol Lihat di tabel untuk memutar video tutorial.</p>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
    <script>
        function loadPreview(link) {
            const videoId = link.split('v=')[1];
            const ampersandPosition = videoId.indexOf('&');
            const cleanId = ampersandPosition !== -1 ? videoId.substring(0, ampersandPosition) : videoId;
            document.getElementById('preview-iframe').src = 'https://www.youtube.com/embed/' + cleanId;
            document.getElementById('preview-container').classList.remove('hidden');
        }
    </script>
@endpush
