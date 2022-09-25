@extends('layout.default')

@section('title', __('data_instituition'))

@section('content')
    <div id="component-content-instituicao"></div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/instituicao/main.js") }}"></script>
@endpush
