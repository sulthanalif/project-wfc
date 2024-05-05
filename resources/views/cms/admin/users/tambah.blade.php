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
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
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
                                <label for="name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input id="name" name="name" type="text" class="form-control w-full"
                                    placeholder="Masukkan Nama Lengkap" required>
                            </div>
                            <div class="mt-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" name="email" type="email" class="form-control w-full"
                                    placeholder="example@gmail.com" required>
                            </div>
                            <div class="mt-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input id="password" name="password" type="password" class="form-control w-full"
                                    placeholder="Masukkan Password" minlength="4" required>
                            </div>
                            <div class="mt-3">
                                <label for="role" class="form-label">Hak Akses <span
                                        class="text-danger">*</span></label>
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
                                <label for="phone_number" class="form-label">Nomor Handphone</label>
                                <input id="phone_number" name="phone_number" type="number" class="form-control w-full"
                                    placeholder="Masukkan Nomor Handphone" maxlength="13">
                            </div>
                            <div class="mt-3">
                                <label for="address" class="form-label">Detail Alamat</label>
                                <input id="address" name="address" type="text" class="form-control w-full"
                                    placeholder="Masukkan Detail Alamat">
                            </div>
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-6 mt-3">
                                    <div>
                                        <label for="rt" class="form-label">RT</label>
                                        <input id="rt" name="rt" type="number" class="form-control w-full"
                                            placeholder="Masukkan Nomor RT">
                                    </div>
                                </div>
                                <div class="col-span-6 mt-3">
                                    <div>
                                        <label for="rw" class="form-label">RW</label>
                                        <input id="rw" name="rw" type="number" class="form-control w-full"
                                            placeholder="Masukkan Nomor RW">
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-12 lg:col-span-6 mt-3">
                                    <div>
                                        <label for="province" class="form-label">Provinsi</label>
                                        <select class="form-select mt-2 sm:mr-2" id="province" name="province">
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-6 mt-3">
                                    <div>
                                        <label for="regency" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select mt-2 sm:mr-2" id="regency" name="regency">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-12 lg:col-span-6 mt-3">
                                    <div>
                                        <label for="district" class="form-label">Kecamatan</label>
                                        <select class="form-select mt-2 sm:mr-2" id="district" name="district">
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-6 mt-3">
                                    <div>
                                        <label for="village" class="form-label">Desa/Kelurahan</label>
                                        <select class="form-select mt-2 sm:mr-2" id="village" name="village">
                                            <option value="">Pilih Desa/Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="image" class="form-label">Upload Foto</label>
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

        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
            .then(response => response.json())
            .then(data => {
                const provinceOption = document.getElementById('province');

                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    provinceOption.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            provinceSelect = document.getElementById('province');
            provinceSelect.addEventListener('change', handleProvinceChange);
            regencySelect = document.getElementById('regency');
            regencySelect.addEventListener('change', handleRegencyChange);
            districtSelect = document.getElementById('district');
            districtSelect.addEventListener('change', handleDistrictChange);
        });

        // Functions
        function handleProvinceChange(event) {
            const provinceId = event.target.value;
            const regencyOption = document.getElementById('regency');

            regencyOption.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

            if (!provinceId) return;

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(regency => {
                        const option = document.createElement('option');
                        option.value = regency.id;
                        option.textContent = regency.name;
                        regencyOption.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function handleRegencyChange(event) {
            const regencyId = event.target.value;
            const districtOption = document.getElementById('district');

            districtOption.innerHTML = '<option value="">Pilih Kecamatan</option>';

            if (!regencyId) return;

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtOption.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function handleDistrictChange(event) {
            const district = event.target.value;
            const villageOption = document.getElementById('village');

            villageOption.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

            if (!district) return;

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${district}.json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(village => {
                        const option = document.createElement('option');
                        option.value = village.id;
                        option.textContent = village.name;
                        villageOption.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

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
