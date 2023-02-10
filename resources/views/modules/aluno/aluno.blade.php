@extends('layout.default')

@section('title', __('dashboard_aluno'))

@section('content')
    <div id="component-content-aluno-acesso"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/perfil/aluno/main.js") }}"></script>
@endpush
