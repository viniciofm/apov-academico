<div>
    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="text-center text-uppercase">
                        <h2>Histórico Escolar {{ $typeDocument == 'parcial' ? 'Parcial' : '' }}</h2>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Dados Pessoais</h5>
                    </div>
                    <div>
                        <div class="row grid-pdf mt-3">
                            <div class="col-md-2 col-2 fw-bolder">Nome:</div>
                            <div class="col-md-2 col-3">{{ $matricula->aluno->usuario->nome ?? '-' }}</div>

                            <div class="col-md-2 col-2 fw-bolder">Matrícula:</div>
                            <div class="col-md-2 col-3">{{ sprintf('%04d', $matricula->aluno->matricula ?? '-') }}</div>
                        </div>
                        <div class="row grid-pdf mt-2">
                            <div class="col-md-2 col-2 fw-bolder">Data de Nascimento:</div>
                            <div class="col-md-2 col-3">{{ $matricula->aluno->data_nascimento ? $matricula->aluno->data_nascimento->format('d/m/Y') : '-' }}</div>

                            <div class="col-md-2 col-2 fw-bolder">CPF:</div>
                            <div class="col-md-2 col-3">{{ $matricula->aluno->usuario->cpf_cnpj ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Dados do Curso</h5>
                    </div>
                    <div>
                        <div class="row grid-pdf mt-3">
                            <div class="col-md-2 col-2 fw-bolder">Sigla:</div>
                            <div class="col-md-2 col-3">{{ $matricula->curso->sigla ?? '-' }}</div>

                            <div class="col-md-2 col-2 fw-bolder">Curso:</div>
                            <div class="col-md-2 col-4">{{ $matricula->curso->nome ?? '-' }}</div>
                        </div>

                        <div class="row grid-pdf mt-2">
                            <div class="col-md-2 col-2  fw-bolder">Mês/Ano Matrícula:</div>
                            <div class="col-md-2 col-3">{{ $matricula->created_at->format('m/Y') }}</div>

                            <div class="col-md-2 col-2  fw-bolder">Mês/Ano Saída:</div>
                            <div class="col-md-2 col-4">{{ in_array($matricula->status, ['concluido', 'cancelado']) ? $matricula->data_saida->format('m/Y') : '-' }}</div>
                        </div>
                        <div class="row grid-pdf mt-2">
                            <div class="col-md-2 col-2 fw-bolder">Status:</div>
                            <div class="col-md-2 col-3">{{ __($matricula->status) }}</div>

                            <div class="col-md-2 col-2 fw-bolder">CNAP:</div>
                            <div class="col-md-2 col-3">{{ $matricula->curso->cnap ?? '-' }}</div>

                            <div class="col-md-1 col-2 fw-bolder">Nº / CBO:</div>
                            <div class="col-md-3 col-7">{{ $matricula->curso->cbo->codigo }} / {{ $matricula->curso->cbo->nome }}</div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Disciplinas Cursadas{{ $typeDocument == 'parcial' ? '/Cursando' : ''}}</h5>
                    </div>
                    <div>
                        <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                            <thead>
                            <tr class="text-uppercase fw-bolder">
                                <td>Sigla</td>
                                <td>Disciplina</td>
                                <td>CH</td>
                                <td>Turma</td>
                                <td>Frequência (%)</td>
                                <td>Nota</td>
                                @if($matricula->status != 'cancelado')
                                    <td>Situação</td>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($matriculasTurma->sortBy('turmaDisciplina.disciplina.nome') as $matriculaTurma)
                                <tr class="{{ !isset($isPDF) && in_array($matriculaTurma->status, ['reprovado', 'reprovado_falta']) ? 'text-danger' : '' }}">
                                    <td> {{ $matriculaTurma->turmaDisciplina->disciplina->sigla }} </td>
                                    <td> {{ $matriculaTurma->turmaDisciplina->disciplina->nome }} </td>
                                    <td> {{ $matriculaTurma->turmaDisciplina->disciplina->carga_horaria }} </td>
                                    <td> {{ $matriculaTurma->turmaDisciplina->turma->codigo }} </td>
                                    <td> {!! number_format($matriculaTurma->frequencia, 2, ',', '.') !!} </td>
                                    <td> {!! number_format($matriculaTurma->nota, 2, ',', '.') !!} </td>
                                    @if($matricula->status != 'cancelado')
                                        <td> {{ __($matriculaTurma->status) }}
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div style="text-align: right;" class="col-md-12">
                            Carga horária cursada: {{ $cargaHorariaCursada }}h
                        </div>
                    </div>

                    <div class="row">
                        <div style="text-align: left;" class="col-md-12">
                            CRITÉRIOS DE AVALIAÇÃO<br>
                            De 0 a 100 pontos<br>
                            APROVAÇÃO: mínimo de 60%
                        </div>
                    </div>

                    @if(Auth::user()->tipo_usuario->nome == 'admin')
                        <div class="row mt-4">
                            <div style="text-align: right;margin-top: 60px;" class="col-md-12">
                                <?php setlocale(LC_ALL, 'pt_BR.UTF-8'); ?>
                                {{ Illuminate\Support\Facades\Auth::user()->instituicao->endereco->cidade->nome }},
                                    {{ now()->format('d') }} de {{  now()->locale('pt-BR')->translatedFormat('F') }} de {{ now()->format('Y') }}.
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div style="text-align: center; margin-top: 60px;" class="col-md-12">
                                <hr style="color: black; margin: 1px auto;height: 1px;width: 50%;" class="">
                                <label style="">Assinatura do Responsável</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

