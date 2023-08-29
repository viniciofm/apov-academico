<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Course\Http\Services\ReportService;
use Modules\Student\Http\Services\MatriculaService;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * @param  Request  $request
     * @return StreamedResponse
     */
    public function exportData(Request $request): StreamedResponse
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
