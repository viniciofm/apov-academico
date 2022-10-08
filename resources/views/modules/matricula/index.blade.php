@extends('layout.default')

@section('title', __('module_matricula'))

@section('content')
    <div id="component-content-matricula"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/matricula/main.js") }}"></script>
@endpush
