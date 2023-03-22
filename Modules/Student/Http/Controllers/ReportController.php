<?php

namespace Modules\Student\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Services\ReportService;
use Modules\Course\Http\Services\TurmaDisciplinaService;
use Modules\Student\Entities\Matricula;
use Modules\Student\Http\Services\MatriculaService;

class ReportController extends Controller
{
    /**
     * @var MatriculaService $matriculaService
     */
    protected $matriculaService;

    /**
     * @param  ReportService  $reportService
     */
    public function __construct(MatriculaService $matriculaService)
    {
        setlocale(LC_COLLATE, 'pt_BR.utf-8');
        $this->matriculaService = $matriculaService;
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportData(Request $request)
    {
        $request = $request->all();
        $data = $this->matriculaService->get([
            'with' => ['turma', 'turma.grade', 'aluno.usuario:id,nome', 'curso', 'empresa'],
            'paginate' => false,
            'perPage' => null,
            'page' => null,
            'search' => $request,
        ]);

        $pdf = \PDF::LoadView('modules.report.pdf.listaMatriculas', array_merge(['matriculas' => $data], $request));
        $pdf->setPaper('a4')
            ->setOption('orientation', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('header-html', view('modules.report.pdf.components.header'))
            ->setOption('footer-center', 'Página [page] de [toPage]')
            ->setOption('footer-font-size', 7)
            ->setOption('footer-left', 'Matrículas')
            ->setOption('footer-right', 'Emitido em: ' . Carbon::now()->format('d/m/Y') . '.');

        return $pdf->stream("matriculas_" . Carbon::now() . ".pdf");
    }
}
