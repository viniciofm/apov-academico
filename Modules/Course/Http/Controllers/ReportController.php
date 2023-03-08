<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Services\ReportService;
use Modules\Student\Entities\Matricula;

class ReportController extends Controller
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
        $this->reportService = $reportService;
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return Application|Factory|View
     */
    public function diarioClasse(TurmaDisciplina $turmaDisciplina)
    {
        return view('modules.report.diarioClasse')->with($this->reportService->getDataTurmaDisciplina($turmaDisciplina));
    }

    /**
     * @param  Matricula  $matricula
     * @return Application|Factory|View
     */
    public function historicoParcial(Matricula $matricula)
    {
        return view('modules.report.historicoParcial')->with(array_merge(['matricula' => $matricula], $this->reportService->getDataByMatricula($matricula)));
    }

    /**
     * @param  Matricula  $matricula
     * @return Application|Factory|View
     */
    public function historicoFinal(Matricula $matricula)
    {
        return view('modules.report.historicoFinal')->with(array_merge(['matricula' => $matricula], $this->reportService->getDataByMatricula($matricula)));
    }

    /**
     * @param  Matricula  $matricula
     * @return Application|Factory|View
     */
    public function boletim(Matricula $matricula)
    {
        return view('modules.report.boletim')->with($this->reportService->getDataTurmaDisciplina(null, $matricula));
    }
}
