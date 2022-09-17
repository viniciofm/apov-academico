@extends('layout.default')

@section('title', __('module_company'))

@section('content')
    <div id="vue-empresa">
        <main-teste></main-teste>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/empresa/main.js") }}"></script>
@endpush
