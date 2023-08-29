<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Services\ReportService;
use Modules\Course\Http\Services\TurmaDisciplinaService;
use Modules\Student\Entities\Matricula;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * @return StreamedResponse
     */
    public function diarioClasse(TurmaDisciplina $turmaDisciplina): StreamedResponse
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

    /**
     * @param  Matricula  $matricula
     * @return mixed
     */
    public function historicoFinal(Matricula $matricula)
    {
        $pdf = \PDF::LoadView('modules.report.pdf.historicoFinal', array_merge(['matricula' => $matricula],$this->reportService->getDataByMatricula($matricula)));
        $pdf->setPaper('a4')
//            ->setOption('orientation', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('header-html', view('modules.report.pdf.components.header'))
            ->setOption('footer-right', 'Página [page] de [toPage]')
            ->setOption('footer-font-size', 7)
            ->setOption('footer-left', 'Histórico Escolar')

            ->setOption('enable-local-file-access', true)
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('keep-relative-links', true);

        return $pdf->stream("historico_" . $matricula->id . ".pdf");
    }

    /**
     * @param  Matricula  $matricula
     * @return StreamedResponse
     */
    public function boletim(Matricula $matricula): StreamedResponse
    {
        $pdf = \PDF::LoadView('modules.report.pdf.boletim', $this->reportService->getDataTurmaDisciplina(null, $matricula));
        $pdf->setPaper('a4')
            ->setOption('orientation', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('header-html', view('modules.report.pdf.components.header'))
            ->setOption('footer-center', 'Página [page] de [toPage]')
            ->setOption('footer-font-size', 7)
            ->setOption('footer-left', 'Boletim Escolar')
            ->setOption('footer-right', 'Emitido em: ' . Carbon::now()->format('d/m/Y') . '.');

        return $pdf->stream("diario_classe_" . $matricula->id . ".pdf");
    }
}
