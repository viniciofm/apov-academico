<div>
    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="text-center text-uppercase">
                        <h2>Histórico Escolar {{ $typeDocument }}</h2>
                    </div>
                </div>
                <div class="row mt-4">
                    <div style="text-align: right;" class="col-md-12">
                        <label>Emitido em: {{ now()->format('d/m/Y')}} às {{ now()->format('H\hi')  }}</label>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Dados Pessoais</h5>
                    </div>
                    <div>
                        <div class="row mt-3">
                            <div class="col-md-2 fw-bolder">Nome:</div>
                            <div class="col-md-2">{{ $matricula->aluno->usuario->nome ?? '-' }}</div>

                            <div class="col-md-2 fw-bolder">Matrícula:</div>
                            <div class="col-md-2">{{ sprintf('%04d', $matricula->aluno->matricula ?? '-') }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2 fw-bolder">Data de Nascimento:</div>
                            <div class="col-md-2">{{ $matricula->aluno->data_nascimento ? $matricula->aluno->data_nascimento->format('d/m/Y') : '-' }}</div>

                            <div class="col-md-2 fw-bolder">CPF:</div>
                            <div class="col-md-2">{{ $matricula->aluno->usuario->cpf_cnpj ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Dados do Curso</h5>
                    </div>
                    <div>
                        <div class="row mt-3">
                            <div class="col-md-2 fw-bolder">Sigla:</div>
                            <div class="col-md-2">{{ $matricula->curso->sigla ?? '-' }}</div>

                            <div class="col-md-2 fw-bolder">Curso:</div>
                            <div class="col-md-2">{{ $matricula->curso->nome ?? '-' }}</div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-2 fw-bolder">Mês/Ano Matrícula:</div>
                            <div class="col-md-2">{{ $matricula->created_at->format('m/Y') }}</div>

                            <div class="col-md-2 fw-bolder">Mês/Ano Saída:</div>
                            <div class="col-md-2">{{ $matricula->created_at->format('m/Y') }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2 fw-bolder">Status:</div>
                            <div class="col-md-2">{{ __($matricula->status) }}</div>

                            <div class="col-md-2 fw-bolder">CNAP:</div>
                            <div class="col-md-2">{{ $matricula->curso->cnap ?? '-' }}</div>

                            <div class="col-md-2 fw-bolder">Nº / CBO:</div>
                            <div class="col-md-2">{{ $matricula->curso->cbo->codigo }} / {{ $matricula->curso->cbo->nome }}</div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Disciplinas Cursadas/Cursando</h5>
                    </div>
                    <div>
                        <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                            <thead>
                            <tr class="text-uppercase fw-bolder">
                                <td>Sigla</td>
                                <td>Disciplina</td>
                                <td>CH</td>
                                <td>Turma</td>
                                <td>Frequência</td>
                                <td>Nota</td>
                                <td>Situação</td>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($matricula->disciplinasMatricula as $disciplinaMatricula)
                                <tr>
                                    <td> {{ $disciplinaMatricula->turmaDisciplina->disciplina->sigla }} </td>
                                    <td> {{ $disciplinaMatricula->turmaDisciplina->disciplina->nome }} </td>
                                    <td> {{ $disciplinaMatricula->turmaDisciplina->disciplina->carga_horaria }} </td>
                                    <td> {{ $disciplinaMatricula->turmaDisciplina->turma->codigo }} </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> {{ __($disciplinaMatricula->status) }}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
