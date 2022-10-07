@extends('layout.default')

@section('title', __('module_turma'))

@section('content')
    <div id="component-content-turma"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/turma/main.js") }}"></script>
@endpush
