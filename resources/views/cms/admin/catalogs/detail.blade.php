@extends('cms.layouts.app', [
    'title' => 'Detail'
])

@section('content')
<div class="container">
    <h1>Detail</h1>

    Nama Katalog : {{ $catalog->name }} <br>
    Deskripsi : {{ $catalog->description }} <br>
    <div class="mb-3">
        <img src="{{ asset('storage/images/catalog/' . $catalog->image) }}" alt="gambar">
        {{ $catalog->image }}
    </div>
    <a href="{{ route('catalog.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
