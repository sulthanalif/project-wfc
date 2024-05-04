@extends('cms.layouts.app', [
    'title' => 'Edit User',
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
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('user.update', $user) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-12 gap-3">
                        @if (in_array($user->roles->first()->name, ['admin', 'super_admin', 'finance_admin']))
                            <div class="col-span-12 lg:col-span-6">
                                <div>
                                    <label for="name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input id="name" name="name" type="text" class="form-control w-full"
                                        value="{{ $user->adminProfile->name ?? '' }}" placeholder="Masukkan Nama Lengkap"
                                        required>
                                </div>
                                <div class="mt-3">
                                    <label for="role" class="form-label">Hak Akses <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select mt-2 sm:mr-2" id="role" name="role" required>
                                        <option value="">Pilih...</option>
                                        <option value="super_admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'super_admin' ? 'selected' : '' }}>
                                            Super Admin</option>
                                        <option value="admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="finance_admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'finance_admin' ? 'selected' : '' }}>
                                            Keuangan</option>
                                        <option value="agent"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'agent' ? 'selected' : '' }}>
                                            Agent</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        @if ($user->roles->first()->name === 'agent')
                            <div class="col-span-12 lg:col-span-6">
                                <div>
                                    <label for="name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                    <input id="name" name="name" type="text" class="form-control w-full"
                                        placeholder="Masukkan Nama Lengkap" value="{{ $user->agentProfile->name }}" required>
                                </div>
                                <div class="mt-3">
                                    <label for="phone_number" class="form-label">Nomor Handphone <span
                                        class="text-danger">*</span></label>
                                    <input id="phone_number" name="phone_number" type="number" class="form-control w-full"
                                        placeholder="Masukkan Nomor Handphone" maxlength="13"
                                        value="{{ $user->agentProfile->phone_number }}" required>
                                </div>
                                <div class="mt-3">
                                    <label for="role" class="form-label">Hak Akses <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select mt-2 sm:mr-2" id="role" name="role" required>
                                        <option value="">Pilih...</option>
                                        <option value="super_admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'super_admin' ? 'selected' : '' }}>
                                            Super Admin</option>
                                        <option value="admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="finance_admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'finance_admin' ? 'selected' : '' }}>
                                            Keuangan</option>
                                        <option value="agent"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'agent' ? 'selected' : '' }}>
                                            Agent</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="image" class="form-label">Upload Foto <span
                                            class="text-danger">*</span></label>
                                    <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                        <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                        <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                        <input id="image" name="image" type="file"
                                            class="w-full h-full top-0 left-0 absolute opacity-0"
                                            onchange="previewFile(this)">
                                    </div>
                                    <div id="image-preview" class="hidden mt-2"></div>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6 mt-3 lg:mt-0" id="agent-fields">
                                <div>
                                    <label for="address" class="form-label">Detail Alamat <span
                                        class="text-danger">*</span></label>
                                    <input id="address" name="address" type="text" class="form-control w-full"
                                        placeholder="Masukkan Detail Alamat" value="{{ $user->agentProfile->address }}" required>
                                </div>
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-6 mt-3">
                                        <div>
                                            <label for="rt" class="form-label">RT <span
                                                class="text-danger">*</span></label>
                                            <input id="rt" name="rt" type="number" class="form-control w-full"
                                                placeholder="Masukkan Nomor RT" value="{{ $user->agentProfile->rt }}" required>
                                        </div>
                                    </div>
                                    <div class="col-span-6 mt-3">
                                        <div>
                                            <label for="rw" class="form-label">RW <span
                                                class="text-danger">*</span></label>
                                            <input id="rw" name="rw" type="number" class="form-control w-full"
                                                placeholder="Masukkan Nomor RW" value="{{ $user->agentProfile->rw }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
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

        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran gambar lebih dari 2MB. Silahkan pilih gambar yang lebih kecil");
                    preview.innerHTML = '';
                    preview.classList.add('hidden');
                    input.value = '';
                    return;
                }

                // Check file type (images only)
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert("Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!!");
                    preview.innerHTML = ''; 
                    preview.classList.add('hidden');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-auto', 'h-40', 'object-cover', 'rounded');
                    preview.innerHTML = ''; 
                    preview.classList.remove('hidden'); 
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
                preview.classList.add('hidden');
            }
        }
    </script>
@endpush
