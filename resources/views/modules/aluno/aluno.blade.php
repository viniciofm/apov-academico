@extends('layout.default')

@section('title', __('dashboard_aluno'))

@section('content')
    <div id="component-content-aluno-acesso"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/aluno_acesso/main.js") }}"></script>
@endpush
