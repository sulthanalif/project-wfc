@extends('cms.layouts.app', ['title' => 'Dashboard'])

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-9">
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: Syarat dan Ketentuan -->
            <div class="col-span-12 lg:col-span-6 mt-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Syarat dan Ketentuan
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <img src="{{ asset('assets/syarat.jpg') }}" alt="Syarat dan Ketentuan" class="img-fluid">
                    </div>
                </div>
            </div>
            <!-- END: Syarat dan Ketentuan -->
            <!-- BEGIN: Form Upload Berkas -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Form Upload Berkas
                    </h2>
                    <a href="" class="ml-auto flex items-center text-primary truncate"> <i
                            data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data </a>
                </div>
                <div class="intro-y box p-5 mt-5">
                    <p>Berkas telah dikirimkan dan sedang ditinjau ulang. Jika ada pertanyaan silahkan hubungi Whatsapp: 0822-1879-9050</p>
                </div>
            </div>
            <!-- END: Form Upload Berkas -->
        </div>
    </div>
</div>
@endsection
