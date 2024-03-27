@extends('cms.admin.layouts.app')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Edit User
    </h2>
</div>

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
                        <div class="input-form mt-3">
                            <label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row"> Email </label>
                            <input id="validation-form-2" type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                        </div>
                        <div class="input-form mt-3">
                            <label for="validation-form-3" class="form-label w-full flex flex-col sm:flex-row"> Password </label>
                            <input id="validation-form-3" type="password" name="password" class="form-control" placeholder="secret" minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-5 mr-1">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-5">Kembali</a>
                    </form>
                    <!-- END: Validation Form -->
                    <!-- BEGIN: Success Notification Content -->
                    <div id="success-notification-content" class="toastify-content hidden flex" >
                        <i class="text-success" data-lucide="check-circle"></i> 
                        <div class="ml-4 mr-4">
                            <div class="font-medium">User berhasil ditambahkan!</div>
                            <div class="text-slate-500 mt-1"> Please check your e-mail for further info! </div>
                        </div>
                    </div>
                    <!-- END: Success Notification Content -->
                    <!-- BEGIN: Failed Notification Content -->
                    <div id="failed-notification-content" class="toastify-content hidden flex" >
                        <i class="text-danger" data-lucide="x-circle"></i> 
                        <div class="ml-4 mr-4">
                            <div class="font-medium">User gagal ditambahkan!</div>
                            <div class="text-slate-500 mt-1"> Please check the fileld form. </div>
                        </div>
                    </div>
                    <!-- END: Failed Notification Content -->
                </div>
            </div>
        </div>
        <!-- END: Form Validation -->
    </div>
</div>
@endsection
