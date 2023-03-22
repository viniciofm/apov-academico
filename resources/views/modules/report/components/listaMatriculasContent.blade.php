<div>
    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="text-center text-uppercase">
                        <h2>Matrículas</h2>
                    </div>
                    @if(!empty($matricula) || !empty($nome_aluno) || !empty($nome_curso) || !empty($status))
                        <h4>Filtros:</h4>
                    @endif
                    @if(!empty($matricula)) <h5>- Matrícula: {{ !empty($matricula) ? $matricula : '-' }} </h5> @endif
                    @if(!empty($nome_aluno)) <h5>- Aluno: {{ !empty($nome_aluno) ? $nome_aluno : '-' }} </h5> @endif
                    @if(!empty($nome_curso)) <h5>- Curso: {{ !empty($nome_curso) ? $nome_curso : '-' }} </h5> @endif
                    @if(!empty($status)) <h5>- Status: {{ !empty($status) ? __($status) : '-' }} </h5> @endif
                </div>

                <div class="row mt-4">
                    <div class="text-center text-uppercase">
                        <h5 style="padding: 6px 0px; background: #999999;">Lista de Matrículas</h5>
                    </div>
                    <div class="">
                        <table class="table table-bordered table-sm" style="font-size:0.8rem;">
                            <thead>
                            <tr class="text-uppercase fw-bolder">
                                <td>Matrícula</td>
                                <td>Aluno</td>
                                <td>Matriz</td>
                                <td>Turma</td>
                                <td>Curso</td>
                                <td>Empresa</td>
                                <td>Status</td>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($matriculas as $matricula)
                                <tr>
                                    <td> {{ sprintf('%04d', $matricula->aluno->matricula) }} </td>
                                    <td> {!! $matricula->aluno->usuario->nome !!} </td>
                                    <td> {!! $matricula->turma->grade->codigo !!} </td>
                                    <td> {!! $matricula->turma->codigo !!} </td>
                                    <td> {!! $matricula->curso->nome !!} </td>
                                    <td> {!! $matricula->empresa ? $matricula->empresa->nome : '-' !!} </td>
                                    <td> {!! __($matricula->status) !!} </td>
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
