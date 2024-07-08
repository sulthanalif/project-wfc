@extends('cms.layouts.app', [
    'title' => 'Edit User',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit User
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('user.update', $user) }}" method="post" enctype="multipart/form-data">
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
                            </div>
                        @endif
                        @if ($user->roles->first()->name === 'agent')
                            <div class="col-span-12 lg:col-span-6">
                                <div>
                                    <label for="name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input id="name" name="name" type="text" class="form-control w-full"
                                        placeholder="Masukkan Nama Lengkap" value="{{ $user->agentProfile->name }}"
                                        required>
                                </div>
                                <div class="mt-3">
                                    <label for="phone_number" class="form-label">Nomor Handphone <span
                                            class="text-danger">*</span></label>
                                    <input id="phone_number" name="phone_number" type="number" class="form-control w-full"
                                        placeholder="Masukkan Nomor Handphone" maxlength="13"
                                        value="{{ $user->agentProfile->phone_number }}" required>
                                </div>
                                <div class="mt-3">
                                    <label for="photo" class="form-label">Upload Foto <span
                                            class="text-danger">*</span></label>
                                    <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                        <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                        <span id="fileName">
                                            <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                        </span>
                                        <input id="photo" name="photo" type="file"
                                            class="w-full h-full top-0 left-0 absolute opacity-0"
                                            onchange="previewFile(this); updateFileName(this)" required>
                                    </div>
                                    <div id="image-preview" class="hidden mt-2"></div>
                                    @if (isset($user->agentProfile->photo))
                                        <div class="mt-2" id="existing-image-preview">
                                            <img src="{{ route('getImage', ['path' => 'photos/' . $user->id, 'imageName' => $user->agentProfile->photo]) }}"
                                                class="w-auto h-40 object-fit-cover rounded">
                                        </div>
                                    @endif
                                    @error('photo')
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
                                        placeholder="Masukkan Detail Alamat" value="{{ $user->agentProfile->address }}"
                                        required>
                                </div>
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-6 mt-3">
                                        <div>
                                            <label for="rt" class="form-label">RT <span
                                                    class="text-danger">*</span></label>
                                            <input id="rt" name="rt" type="number" class="form-control w-full"
                                                placeholder="Masukkan Nomor RT" value="{{ $user->agentProfile->rt }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-span-6 mt-3">
                                        <div>
                                            <label for="rw" class="form-label">RW <span
                                                    class="text-danger">*</span></label>
                                            <input id="rw" name="rw" type="number" class="form-control w-full"
                                                placeholder="Masukkan Nomor RW" value="{{ $user->agentProfile->rw }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-12 lg:col-span-6 mt-3">
                                        <div>
                                            <label for="province" class="form-label">Provinsi <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mt-2 sm:mr-2" id="province" name="province"
                                                required>
                                                <option value="">Pilih Provinsi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-6 mt-3">
                                        <div>
                                            <label for="regency" class="form-label">Kota/Kabupaten <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mt-2 sm:mr-2" id="regency" name="regency"
                                                required>
                                                <option value="">Pilih Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-12 lg:col-span-6 mt-3">
                                        <div>
                                            <label for="district" class="form-label">Kecamatan <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mt-2 sm:mr-2" id="district" name="district"
                                                required>
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-6 mt-3">
                                        <div>
                                            <label for="village" class="form-label">Desa/Kelurahan <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mt-2 sm:mr-2" id="village" name="village"
                                                required>
                                                <option value="">Pilih Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran gambar lebih dari 2MB. Silahkan pilih gambar yang lebih kecil");
                    preview.innerHTML = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                // Check file type (images only)
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert("Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!!");
                    preview.innerHTML = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-auto', 'h-40', 'object-cover', 'rounded'); // Adjust size and styles as needed
                    document.getElementById('existing-image-preview').innerHTML = '';
                    preview.innerHTML = ''; // Clear previous previews
                    preview.classList.remove('hidden'); // Show the preview container
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = ''; // Clear any existing preview
                preview.classList.add('hidden'); // Hide the preview container
            }
        }

        const dataVillage = "{{ $user->agentProfile->village }}";
        const dataDistrict = "{{ $user->agentProfile->district }}";
        const dataRegency = "{{ $user->agentProfile->regency }}";
        const dataProvince = "{{ $user->agentProfile->province }}";

        document.addEventListener('DOMContentLoaded', () => {
            const provinceSelect = document.getElementById('province');
            const regencySelect = document.getElementById('regency');
            const districtSelect = document.getElementById('district');
            const villageSelect = document.getElementById('village');

            // Fetch provinces
            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(response => response.json())
                .then(data => {
                    data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        if (province.id == dataProvince) {
                            option.selected = true;
                        }
                        provinceSelect.appendChild(option);
                    });
                    // Trigger change event to load regencies
                    handleProvinceChange({
                        target: {
                            value: dataProvince
                        }
                    });
                })
                .catch(error => console.error('Error fetching provinces:', error));

            // Event listeners
            provinceSelect.addEventListener('change', handleProvinceChange);
            regencySelect.addEventListener('change', handleRegencyChange);
            districtSelect.addEventListener('change', handleDistrictChange);

            // Functions
            function handleProvinceChange(event) {
                const provinceId = event.target.value;
                regencySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

                if (!provinceId) return;

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(regency => {
                            const option = document.createElement('option');
                            option.value = regency.id;
                            option.textContent = regency.name;
                            if (regency.id == dataRegency) {
                                option.selected = true;
                            }
                            regencySelect.appendChild(option);
                        });
                        // Trigger change event to load districts
                        handleRegencyChange({
                            target: {
                                value: dataRegency
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching regencies:', error));
            }

            function handleRegencyChange(event) {
                const regencyId = event.target.value;
                districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

                if (!regencyId) return;

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            if (district.id == dataDistrict) {
                                option.selected = true;
                            }
                            districtSelect.appendChild(option);
                        });
                        // Trigger change event to load villages
                        handleDistrictChange({
                            target: {
                                value: dataDistrict
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching districts:', error));
            }

            function handleDistrictChange(event) {
                const districtId = event.target.value;
                villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

                if (!districtId) return;

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(village => {
                            const option = document.createElement('option');
                            option.value = village.id;
                            option.textContent = village.name;
                            if (village.id == dataVillage) {
                                option.selected = true;
                            }
                            villageSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching villages:', error));
            }
        });
    </script>
@endpush
