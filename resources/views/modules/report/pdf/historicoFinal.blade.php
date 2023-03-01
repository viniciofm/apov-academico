@extends('layout.pdf')

@section('title', __('module_report'))

@section('content')
    <div>
        @include('modules.report.components.historicoContent', array('typeDocument' => 'final'))
    </div>
@endsection
