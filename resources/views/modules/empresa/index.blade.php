@extends('layout.default')

@section('title', __('module_company'))

@section('content')
    <div id="component-content-empresa">

    </div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/empresa/main.js") }}"></script>
@endpush
