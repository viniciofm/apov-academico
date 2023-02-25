<?php

namespace Modules\Course\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Services\ReportService;
use Modules\Course\Http\Services\TurmaDisciplinaService;
use Modules\Student\Entities\Matricula;

class ReportPdfController extends Controller
{
    /**
     * @var ReportService $reportService
     */
    protected $reportService;

    /**
     * @param  ReportService  $reportService
     */
    public function __construct(ReportService $reportService)
    {
        setlocale(LC_COLLATE, 'pt_BR.utf-8');
        $this->reportService = $reportService;
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return Application|Factory|View
     */
    public function diarioClasse(TurmaDisciplina $turmaDisciplina)
    {
        $pdf = \PDF::LoadView('modules.report.pdf.diarioClasse', $this->reportService->getDataTurmaDisciplina($turmaDisciplina));
        $pdf->setPaper('a4')
            ->setOption('orientation', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('header-html', view('modules.report.pdf.components.header'))
            ->setOption('footer-center', 'Página [page] de [toPage]')
            ->setOption('footer-font-size', 7)
            ->setOption('footer-left', 'Diário de Classe')
            ->setOption('footer-right', 'Emitido em: ' . Carbon::now()->format('d/m/Y') . '.');

        return $pdf->stream("diario_classe_" . $turmaDisciplina->id . ".pdf");
    }

    /**
     * @param  Matricula  $matricula
     * @return mixed
     */
    public function historicoParcial (Matricula $matricula)
    {
        $pdf = \PDF::LoadView('modules.report.pdf.historicoParcial', array_merge(['matricula' => $matricula],$this->reportService->getDataByMatricula($matricula)));
        $pdf->setPaper('a4')
//            ->setOption('orientation', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('header-html', view('modules.report.pdf.components.header'))
            ->setOption('footer-right', 'Página [page] de [toPage]')
            ->setOption('footer-font-size', 7)
            ->setOption('footer-left', 'Histórico Escolar Parcial')

            ->setOption('enable-local-file-access', true)
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('keep-relative-links', true);

        return $pdf->stream("historico_parcial_" . $matricula->id . ".pdf");
    }
}
