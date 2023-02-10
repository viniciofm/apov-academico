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
                    <h5>Professor: {{ $turmaDisciplina->professor->usuario->nome }} </h5>
                </div>

                @if(count($turmaDisciplina->aulas))
                    <div class="row mt-4">
                        <div class="text-center text-uppercase">
                            <h5>Frequência</h5>
                        </div>
                        <div class="">
                            <table class="table table-striped table-sm" style="font-size:0.8rem;">
                                <thead>
                                    <tr class="text-uppercase">
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
                                            <td> {{ $matriculaTurma->matricula->aluno->matricula }} </td>
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
                            <h5>Notas</h5>
                        </div>
                        <div class="">
                            <table class="table table-striped table-sm" style="font-size:0.8rem;">
                                <thead>
                                    <tr class="text-uppercase">
                                        <td>Matrícula</td>
                                        <td>Aluno</td>
                                        @foreach($turmaDisciplina->atividades as $atividade)
                                            <td> {!! $atividade->titulo !!} </td>
                                        @endforeach
                                        <td>Nota Final</td>
                                        <td>Faltas</td>
                                        <td>Frequência (%)</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($matriculasTurma as $matriculaTurma)
                                        <tr>
                                            <td> {{ $matriculaTurma->matricula->aluno->matricula }} </td>
                                            <td> {!! $matriculaTurma->matricula->aluno->usuario->nome !!} </td>

                                            @foreach($turmaDisciplina->atividades as $atividade)
                                                @php $nota = $atividade->notas->where('matricula_id', $matriculaTurma->matricula_id)->first(); @endphp
                                                <td> {{ $nota ? number_format($nota->nota, 2, ',', '.') : '-' }} </td>
                                            @endforeach

                                            <td> {!! number_format($matriculaTurma->nota, 2, ',', '.') !!} </td>
                                            <td> {!! $matriculaTurma->faltas !!} </td>
                                            <td> {!! ( $matriculaTurma->faltas * 100) / $numAulas !!} </td>
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
                            <h5>Conteúdo Programático</h5>
                        </div>
                        <div>
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-uppercase">
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
