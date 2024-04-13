@extends('cms.layouts.app', [
    'title' => 'Tambah Sub Agen',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Sub Agen
        </h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error')  }}
            <button type="button"
                class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                    class="w-4 h-4"></i> </button>
                </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('sub-agent.store') }}" method="post">
                    @csrf
                            <div>
                                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input id="name" name="name" type="text" class="form-control w-full" placeholder="Masukkan Nama Lengkap Sub Agent" required>
                            </div>
                            @hasrole('super_admin')
                            <div class="mt-3">
                                <label for="agent_id" class="form-label">Dari Agent <span class="text-danger">*</span></label>
                                <select class="form-select mt-2 sm:mr-2" id="agent_id" name="agent_id" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->agentProfile->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endhasrole
                            @hasrole('agent')
                            <input type="hidden" name="agent_id" value="{{ auth()->user()->id }}">
                            @endhasrole
                            <div class="mt-3">
                                <label>Alamat Lengkap <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                <textarea id="address" name="address" class="editor">
                                    Masukkan Alamat Lengkap
                                </textarea>
                                </div>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="phone_number" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input id="phone_number" name="phone_number" type="number" class="form-control w-full" placeholder="Masukkan Nomor Telepon" minlength="11" maxlength="13" required>
                            </div>
                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 mr-1">Kembali</a>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
@endpush