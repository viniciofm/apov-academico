@extends('layout.default')

@section('title', __('index'))

@section('content')
    <div class="header">
        <h1 class="header-title">
            {{ $saudacaoUser }}, {{ $currentUser->nome }}!
        </h1>
        <p class="header-subtitle"></p>
    </div>
@endsection

@push('scripts')

@endpush
