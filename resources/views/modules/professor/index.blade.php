@extends('layout.default')

@section('title', __('module_teacher'))

@section('content')
    <div id="component-content-professor"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/professor/main.js") }}"></script>
@endpush
