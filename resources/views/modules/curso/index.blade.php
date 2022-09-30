@extends('layout.default')

@section('title', __('module_course'))

@section('content')
    <div id="component-content-curso"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/curso/main.js") }}"></script>
@endpush
