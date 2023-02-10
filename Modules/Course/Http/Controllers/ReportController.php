<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Services\ReportService;

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
        return view('modules.report.diarioClasse')->with($this->reportService->dataDiarioClasse($turmaDisciplina));
    }
}
