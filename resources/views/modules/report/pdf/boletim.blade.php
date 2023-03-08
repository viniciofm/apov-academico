@extends('layout.pdf')

@section('title', __('module_report'))

@section('content')
    <div>
        @include('modules.report.components.boletimContent')
    </div>
@endsection
