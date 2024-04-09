@extends('cms.layouts.app', [
    'title' => 'Tambah User',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah User
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
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 lg:col-span-6">
                            <div>
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" name="email" type="email" class="form-control w-full" placeholder="example@gmail.com" required>
                            </div>
                            <div class="mt-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input id="password" name="password" type="password" class="form-control w-full" placeholder="Masukkan Password" minlength="4" required>
                            </div>
                            <div class="mt-3">
                                <label for="role" class="form-label">Hak Akses <span class="text-danger">*</span></label>
                                <select class="form-select mt-2 sm:mr-2" id="role" name="role" required>
                                    <option value="">Pilih...</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="admin">Admin</option>
                                    <option value="finance_admin">Keuangan</option>
                                    <option value="agent">Agent</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="col-span-12 lg:col-span-6 mt-3 lg:mt-0" id="agent-fields" style="display: none">
                            <div>
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" name="name" type="text" class="form-control w-full" placeholder="Masukkan Nama Lengkap">
                            </div>
                            <div class="mt-3">
                                <label for="phone_number" class="form-label">Nomor Handphone</label>
                                <input id="phone_number" name="phone_number" type="number" class="form-control w-full" placeholder="Masukkan Nomor Handphone" maxlength="13">
                            </div>
                            <div class="mt-3">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea id="address" name="address" class="form-control w-full" placeholder="Masukkan Alamat Lengkap" minlength="5"></textarea>
                            </div>
                        </div>
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