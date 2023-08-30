<div>
    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="text-center text-uppercase">
                        <h2>Boletim Escolar</h2>
                    </div>
                    <h5>Matrícula: {{ sprintf('%04d', $matricula->aluno->matricula ?? '-') }} </h5>
                    <h5>Aluno: {{ $matricula->aluno->usuario->nome }} </h5>
                    <h5>Curso: {{ $matricula->curso->nome }} </h5>
                    <h5>Turma: {{ $matricula->turma->codigo }} </h5>
                </div>

                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <div class="text-center text-uppercase">
                            <h4>Disciplinas</h4>
                        </div>

                        @foreach($matricula->disciplinasMatricula->sortBy('turmaDisciplina.disciplina.nome') as $discMatricula)
                            <div class="row border border-1 rounded mb-4" style="border-color: #555555;">
                                <h5 style="padding: 6px 0px; background: #999999;">{{ $discMatricula->turmaDisciplina->disciplina->nome }}</h5>

                                @if(count($discMatricula->turmaDisciplina->aulas))
                                    <div class="row">
                                        <h5 style="padding: 6px 0px;">Frequência</h5>
                                        <div>
                                            <table id="table_frequencia" class="table table-bordered table-sm" style="{{ count($discMatricula->turmaDisciplina->aulas) < 40 ? 'font-size:0.8rem;' : 'font-size:0.6rem;' }}">
                                                <thead>
                                                <tr class="text-uppercase fw-bolder">
                                                    @foreach($discMatricula->turmaDisciplina->aulas as $aula)
                                                        <td> {!! $aula->data->format('d/<\b\r>m') !!} </td>
                                                    @endforeach
                                                </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        @foreach($discMatricula->turmaDisciplina->aulas as $aula)
                                                            <td> {{ $aula->faltas->where('matricula_id', $matricula->id)->first() ? 'F' : '*' }} </td>
                                                        @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if(count($discMatricula->turmaDisciplina->atividades))
                                    <div class="row mt-1">
                                        <div class="text-center text-uppercase">
                                            <h5 style="padding: 6px 0px;">Notas</h5>
                                        </div>

                                        <div class="">
                                            <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                                                <thead>
                                                <tr class="text-uppercase fw-bolder">
                                                    @foreach($discMatricula->turmaDisciplina->atividades as $atividade)
                                                        <td> {!! $atividade->titulo !!} </td>
                                                    @endforeach
                                                    <td>Nota Final</td>
                                                    <td>Faltas</td>
                                                    <td>Frequência (%)</td>
                                                    <td>Situação</td>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                    <tr class="{{ in_array($discMatricula->status, ['reprovado', 'reprovado_falta']) ? 'text-danger' : '' }}">
                                                        @foreach($discMatricula->turmaDisciplina->atividades as $atividade)
                                                            @php $nota = $atividade->notas->where('matricula_id', $matricula->id)->first(); @endphp
                                                            <td> {{ $nota ? number_format($nota->nota, 2, ',', '.') : '-' }} </td>
                                                        @endforeach

                                                        <td> {!! number_format($discMatricula->nota_final, 2, ',', '.') !!} </td>
                                                        <td> {!! $discMatricula->faltas !!} </td>
                                                        <td> {!! number_format($discMatricula->frequencia, 2, ',', '.') !!} </td>
                                                        <td> {{ __($discMatricula->status) }}
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if(!count($discMatricula->turmaDisciplina->aulas) && !count($discMatricula->turmaDisciplina->atividades))
                                    <div class="text-center text-uppercase">
                                        <h6>-</h6>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #table_frequencia tr td {
        padding: 1px;
    }
</style>
