@extends('layout.default')

@section('title', __('module_usuario'))

@section('content')
    <div id="component-content-usuario"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/usuario/main.js") }}"></script>
@endpush
