<div>
    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="text-center text-uppercase">
                        <h2>Diário de Classe</h2>
                    </div>
                    <h5>Curso: {{ $turmaDisciplina->turma->grade->curso->nome }} </h5>
                    <h5>Turma: {{ $turmaDisciplina->turma->codigo }} </h5>
                    <h5>Sigla - Disciplina: {{ $turmaDisciplina->disciplina->sigla . ' - ' . $turmaDisciplina->disciplina->nome }} </h5>
                    <h5>Professor: {{ $turmaDisciplina->professor ? $turmaDisciplina->professor->usuario->nome : '-' }} </h5>
                </div>

                @if(count($turmaDisciplina->aulas))
                    <div class="row mt-4">
                        <div class="text-center text-uppercase">
                            <h5 style="padding: 6px 0px; background: #999999;">Frequência</h5>
                        </div>
                        <div class="">
                            <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                                <thead>
                                    <tr class="text-uppercase fw-bolder">
                                        <td>Matrícula</td>
                                        <td>Aluno</td>
                                        @foreach($turmaDisciplina->aulas as $aula)
                                            <td> {!! $aula->data->format('d/<\b\r>m') !!} </td>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($matriculasTurma as $matriculaTurma)
                                        <tr>
                                            <td> {{ sprintf('%04d', $matriculaTurma->matricula->aluno->matricula) }} </td>
                                            <td> {!! $matriculaTurma->matricula->aluno->usuario->nome !!} </td>
                                            @foreach($turmaDisciplina->aulas as $aula)
                                                <td> {{ $aula->faltas->where('matricula_id', $matriculaTurma->matricula_id)->first() ? 'F' : '*' }} </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <br>

                @if(count($turmaDisciplina->atividades))
                    <div class="row mt-4">
                        <div class="text-center text-uppercase">
                            <h5 style="padding: 6px 0px; background: #999999;">Notas</h5>
                        </div>
                        <div class="">
                            <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                                <thead>
                                    <tr class="text-uppercase fw-bolder">
                                        <td>Matrícula</td>
                                        <td>Aluno</td>
                                        @foreach($turmaDisciplina->atividades as $atividade)
                                            <td> {!! $atividade->titulo !!} </td>
                                        @endforeach
                                        <td>Nota Final</td>
                                        <td>Faltas</td>
                                        <td>Frequência (%)</td>
                                        <td>Situação</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($matriculasTurma as $matriculaTurma)
                                        <tr class="{{ in_array($matriculaTurma->status, ['reprovado', 'reprovado_falta']) ? 'text-danger' : '' }}">
                                            <td> {{ sprintf('%04d', $matriculaTurma->matricula->aluno->matricula) }} </td>
                                            <td> {!! $matriculaTurma->matricula->aluno->usuario->nome !!} </td>

                                            @foreach($turmaDisciplina->atividades as $atividade)
                                                @php $nota = $atividade->notas->where('matricula_id', $matriculaTurma->matricula_id)->first(); @endphp
                                                <td> {{ $nota ? number_format($nota->nota, 2, ',', '.') : '-' }} </td>
                                            @endforeach

                                            <td> {!! number_format($matriculaTurma->nota_final, 2, ',', '.') !!} </td>
                                            <td> {!! $matriculaTurma->faltas !!} </td>
                                            <td> {!! number_format($matriculaTurma->frequencia, 2, ',', '.') !!} </td>
                                            <td> {{ __($matriculaTurma->status) }}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <br>

                @if(count($turmaDisciplina->aulas))
                    <div class="row mt-4">
                        <div class="text-center text-uppercase">
                            <h5 style="padding: 6px 0px; background: #999999;">Conteúdo Programático</h5>
                        </div>
                        <div>
                            <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                                <thead>
                                    <tr class="text-uppercase fw-bolder">
                                        <td>Data</td>
                                        <td>Conteúdo</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($turmaDisciplina->aulas as $aula)
                                        <tr>
                                            <td> {{ $aula->data->format('d/m/Y') }} </td>
                                            <td> {!! $aula->conteudo !!} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
