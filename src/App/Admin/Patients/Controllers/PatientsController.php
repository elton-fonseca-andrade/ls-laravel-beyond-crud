<?php

namespace App\Admin\Patients\Controllers;

use App\Admin\Patients\ViewModels\PatientReportViewModel;
use Carbon\Carbon;
use Domain\Patients\Actions\GeneratePatientReportAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientsController
{
    public function index(Request $request, GeneratePatientReportAction $action): JsonResponse
    {
        $start = Carbon::parse($request->query('start', now()->startOfYear()->toDateString()));
        $end = Carbon::parse($request->query('end', now()->endOfYear()->toDateString()));

        $viewModel = new PatientReportViewModel($start, $end, $action);

        return response()->json($viewModel->toArray());
    }
}
