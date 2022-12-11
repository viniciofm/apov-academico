@extends('layout.default')

@section('title', __('module_teacher'))

@section('content')
    <div id="component-my-disciplines"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/perfil/professor/main.js") }}"></script>
@endpush
