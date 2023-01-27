@extends('layout.default')

@section('title', __('module_company'))

@section('content')
    <div id="component-content-empresa-acesso"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/empresa_acesso/main.js") }}"></script>
@endpush
