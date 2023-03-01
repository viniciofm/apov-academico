@extends('layout.report')

@section('title', __('module_report'))

@section('content')
    <div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            @include('components.sub-header', array('links' => [], 'module' => 'Relatório', 'title' => 'Histórico Final'))

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div style="text-align: right;" class="col-md-12">
                            <a target="_blank" href="{{ route('relatorio.pdf.historico-final', $matricula)  }}"
                               class="btn btn-success" title="Gerar documento em PDF">
                                <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        @include('modules.report.components.historicoContent', array('typeDocument' => 'final'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
