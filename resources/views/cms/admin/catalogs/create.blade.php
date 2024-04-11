@extends('cms.layouts.app', [
    'title' => 'Tambah Katalog'
])

@section('content')
@if (session('error'))
<div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
    <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error')  }}
    <button type="button"
        class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
            class="w-4 h-4"></i> </button>
        </div>
@endif
<h1>Tambah Katalog</h1>
<form method="POST" action="{{ route('catalog.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Nama Katalog</label>
      <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
      {{-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> --}}
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Deskripsi</label>
      <textarea id="description" class="form-control" name="description" placeholder="Type your comments" minlength="10" required></textarea>
      @error('description')
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
              </span>
      @enderror
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Gambar</label>
        <input type="file" name="image" class="form-control" id="image" aria-describedby="emailHelp" accept="image/*" required>
        {{-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> --}}
      </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary mt-5">Kembali</a>
  </form>
@endsection
