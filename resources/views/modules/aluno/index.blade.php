@extends('layout.default')

@section('title', __('module_aluno'))

@section('content')
    <div id="component-content-aluno"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/aluno/main.js") }}"></script>
@endpush
