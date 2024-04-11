@extends('cms.layouts.app', [
    'title' => 'Edit',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit User
        </h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                    class="w-4 h-4"></i> </button>
        </div>
    @endif

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-6">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">
            <div id="form-validation" class="p-5">
                <div class="preview">
                    <!-- BEGIN: Validation Form -->
                    <form class="validate-form">
                        <div class="input-form">
                            <label for="validation-form-1" class="form-label w-full flex flex-col sm:flex-row"> Nama Lengkap </label>
                            <input id="validation-form-1" type="text" name="name" class="form-control" placeholder="Salwa" minlength="2" required>
                        </div>

                        @if ($user->roles->first()->name === 'agent')
                            <div class="col-span-12 lg:col-span-6 mt-3 lg:mt-0" id="agent-fields" style="display: none">
                                <div>
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input id="name" name="name" type="text" class="form-control w-full"
                                        placeholder="Masukkan Nama Lengkap" value="{{ $user->agentProfile->name }}">
                                </div>
                                <div class="mt-3">
                                    <label for="phone_number" class="form-label">Nomor Handphone</label>
                                    <input id="phone_number" name="phone_number" type="number" class="form-control w-full"
                                        placeholder="Masukkan Nomor Handphone" maxlength="13"
                                        value="{{ $user->agentProfile->phone_number }}">
                                </div>
                                <div class="mt-3">
                                    <label for="address" class="form-label">Alamat Lengkap</label>
                                    <textarea id="address" name="address" class="form-control w-full" placeholder="Masukkan Alamat Lengkap"
                                        minlength="5">{{ $user->agentProfile->address }}</textarea>
                                </div>
                            </div>
                        @endif
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
    <script>
        const roleSelect = document.getElementById('role');
        const agentFields = document.getElementById('agent-fields');

        roleSelect.addEventListener('change', (event) => {
            if (event.target.value === 'agent') {
                agentFields.style.display = 'block';
            } else {
                agentFields.style.display = 'none';
            }
        });
    </script>
@endpush
