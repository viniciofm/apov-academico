@extends('layout.default')

@section('title', __('module_company'))

@section('content')
    <div id="component-content-empresa-acesso"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/perfil/empresa/main.js") }}"></script>
@endpush
