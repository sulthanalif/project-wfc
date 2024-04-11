@extends('cms.layouts.app', [
    'title' => 'Katalog'
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
<h1>Katalog</h1>
    <a href="{{ route('catalog.create') }}" class="btn btn-primary">Tambah</a>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">nama</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Gambar</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
            @if ($catalogs->isEmpty())
                <tr>
                    <td colspan="5">Belum Ada Data</td>
                </tr>
            @else
                @foreach ($catalogs as $catalog)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td><a href="{{ route('catalog.show', $catalog) }}">{{ $catalog->name }}</a></td>
                        <td>{{ $catalog->description }}</td>
                        <td><img src="{{ asset('storage/images/catalog/' . $catalog->image) }}" alt="gambar"></td>
                        <td>
                            <a href="{{ route('catalog.edit', $catalog) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('catalog.destroy', $catalog) }}" method="post" onsubmit="return confirm('apakah anda yakin?')">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="page" value="{{ $catalogs->currentPage() }}">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
      <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $catalogs->links('cms.layouts.paginate') }}
    </div>
@endsection

