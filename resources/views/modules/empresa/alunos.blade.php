@extends('layout.default')

@section('title', __('module_company'))

@section('content')
    <div id="component-content-empresa-aluno"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/perfil/empresa_aluno/main.js") }}"></script>
@endpush
