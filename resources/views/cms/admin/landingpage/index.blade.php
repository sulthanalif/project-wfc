@extends('cms.layouts.app', [
    'title' => 'Paket',
])

@section('content')

    @include('cms.admin.landingpage.form.header')

@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>

@endpush
