@extends('layout.pdf')

@section('title', __('module_report'))

@section('content')
    <div>
        @include('modules.report.components.diarioClasseContent', array('turmaDisciplina' => $turmaDisciplina))
    </div>
@endsection
