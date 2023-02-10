@extends('layout.report')

@section('title', __('module_report'))

@section('content')
    <div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            @include('components.sub-header', array('links' => [], 'module' => 'Relatório', 'title' => 'Diário de Classe'))

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        @include('modules.report.components.diarioClasseContent', array('turmaDisciplina' => $turmaDisciplina))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
