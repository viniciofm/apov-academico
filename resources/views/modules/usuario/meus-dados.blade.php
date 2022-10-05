@extends('layout.default')

@section('title', __('user_data'))

@section('content')
    <div id="component-content-usuario-edit">
        <usuario :edit-user="true"></usuario>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix("/dist/js/usuario/main.js") }}"></script>
@endpush
