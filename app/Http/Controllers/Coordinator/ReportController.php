<?php

namespace App\Http\Controllers\Coordinator;

use App\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coordinator\DestroyBimestralReport;
use App\Http\Requests\Coordinator\DestroyFinalReport;
use App\Http\Requests\Coordinator\StoreBimestralReport;
use App\Http\Requests\Coordinator\StoreFinalReport;
use App\Http\Requests\Coordinator\UpdateBimestralReport;
use App\Http\Requests\Coordinator\UpdateFinalReport;
use App\Models\BimestralReport;
use App\Models\Course;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\State;
use App\Models\SystemConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('coordinator');
        $this->middleware('permission:report-list');
        $this->middleware('permission:report-create', ['only' => ['createBimestral', 'createFinal', 'storeBimestral', 'storeFinal']]);
        $this->middleware('permission:report-edit', ['only' => ['editBimestral', 'editFinal', 'updateBimestral', 'updateFinal']]);
        $this->middleware('permission:report-delete', ['only' => ['destroyBimestral', 'destroyFinal']]);
    }

    public function index()
    {
        $courses = Auth::user()->coordinator_of;

        $bReports = BimestralReport::all()->filter(function (BimestralReport $report) use ($courses) {
            return $courses->contains($report->internship->student->course);
        });

        $fReports = FinalReport::all()->filter(function (FinalReport $report) use ($courses) {
            return $courses->contains($report->internship->student->course);
        });

        return view('coordinator.report.index')->with(['bReports' => $bReports, 'fReports' => $fReports]);
    }

    public function createBimonthly()
    {
        $courses = Auth::user()->coordinator_of;

        $internships = Internship::actives()->where('state_id', '=', State::OPEN)->orderBy('id')->get()
            ->filter(function (Internship $internship) use ($courses) {
                return $courses->contains($internship->student->course);
            });

        $i = request()->i;
        return view('coordinator.report.bimonthly.new')->with(['internships' => $internships, 'i' => $i]);
    }

    public function createFinal()
    {
        $courses = Auth::user()->coordinator_of;

        $internships = Internship::actives()->where('state_id', '=', State::OPEN)->orderBy('id')->get()
            ->filter(function (Internship $internship) use ($courses) {
                return $courses->contains($internship->student->course);
            });

        $i = request()->i;
        return view('coordinator.report.final.new')->with(['internships' => $internships, 'i' => $i]);
    }

    public function editBimonthly($id)
    {
        $report = BimestralReport::findOrFail($id);

        $courses = Auth::user()->coordinator_of;

        $internships = Internship::actives()->where('state_id', '=', State::OPEN)->get()
            ->filter(function (Internship $internship) use ($courses) {
                return $courses->contains($internship->student->course);
            })->merge([$report->internship])->sortBy('id');

        return view('coordinator.report.bimonthly.edit')->with(['report' => $report, 'internships' => $internships]);
    }

    public function editFinal($id)
    {
        $report = FinalReport::findOrFail($id);

        $courses = Auth::user()->coordinator_of;

        $internships = Internship::actives()->where('state_id', '=', State::OPEN)->get()
            ->filter(function (Internship $internship) use ($courses) {
                return $courses->contains($internship->student->course);
            })->merge([$report->internship])->sortBy('id');

        return view('coordinator.report.final.edit')->with(['report' => $report, 'internships' => $internships]);
    }

    public function storeBimonthly(StoreBimestralReport $request)
    {
        $report = new BimestralReport();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Novo relatório bimestral";
        $log .= "\nUsuário: {$user->name}";

        $report->internship_id = $validatedData->internship;
        $report->date = $validatedData->date;
        $report->protocol = $validatedData->protocol;
        $saved = $report->save();
        $log .= "\nNovos dados: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar relatório bimestral");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.report.index')->with($params);
    }

    public function storeFinal(StoreFinalReport $request)
    {
        $report = new FinalReport();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Novo relatório final";
        $log .= "\nUsuário: {$user->name}";

        $report->internship_id = $validatedData->internship;
        $report->date = $validatedData->reportDate;

        $course = $report->internship->student->course;

        $report->grade_1_a = $validatedData->grade_1_a;
        $report->grade_1_b = $validatedData->grade_1_b;
        $report->grade_1_c = $validatedData->grade_1_c;
        $report->grade_2_a = $validatedData->grade_2_a;
        $report->grade_2_b = $validatedData->grade_2_b;
        $report->grade_2_c = $validatedData->grade_2_c;
        $report->grade_2_d = $validatedData->grade_2_d;
        $report->grade_3_a = $validatedData->grade_3_a;
        $report->grade_3_b = $validatedData->grade_3_b;
        $report->grade_4_a = $validatedData->grade_4_a;
        $report->grade_4_b = $validatedData->grade_4_b;
        $report->grade_4_c = $validatedData->grade_4_c;

        $report->final_grade = round(($report->grade_1_a * 5 + $report->grade_1_b * 4 + $report->grade_1_c * 2 + $report->grade_2_a * 3 + $report->grade_2_b * 4 + $report->grade_2_c * 3 + $report->grade_2_d * 1 + $report->grade_3_a * 5 + $report->grade_3_b * 4 + $report->grade_4_a * 2 + $report->grade_4_b * 2 + $report->grade_4_c * 5) / 24, 1);
        $report->completed_hours = $validatedData->completedHours;
        $report->end_date = $validatedData->endDate;
        $report->approval_number = $this->generateApprovalNumber($course);
        $report->observation = $validatedData->observation;

        $coordinator = Auth::user()->coordinators->where('course_id', '=', $course->id)->last();
        $coordinator_id = $coordinator->temporary_of->id ?? $coordinator->id;
        $report->coordinator_id = $coordinator_id;

        $saved = $report->save();
        $log .= "\nNovos dados: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $minGrade = $report->internship->student->course_configuration->min_grade;
        if ($saved) {
            Log::info($log);
            $report->internship->state_id = ($report->final_grade >= $minGrade) ? State::FINISHED : State::INVALID;
            $report->internship->save();
        } else {
            Log::error("Erro ao salvar relatório final");
        }

        $params['saved'] = $saved;
        $params['id'] = ($saved) ? $report->id : null;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.report.index')->with($params);
    }

    public function updateBimonthly($id, UpdateBimestralReport $request)
    {
        $report = BimestralReport::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de relatório bimestral";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $report->date = $validatedData->date;
        $report->protocol = $validatedData->protocol;
        $saved = $report->save();
        $log .= "\nNovos dados: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar relatório bimestral");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.report.index')->with($params);
    }

    public function updateFinal($id, UpdateFinalReport $request)
    {
        $report = FinalReport::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de relatório final";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $report->date = $validatedData->reportDate;

        $report->grade_1_a = $validatedData->grade_1_a;
        $report->grade_1_b = $validatedData->grade_1_b;
        $report->grade_1_c = $validatedData->grade_1_c;
        $report->grade_2_a = $validatedData->grade_2_a;
        $report->grade_2_b = $validatedData->grade_2_b;
        $report->grade_2_c = $validatedData->grade_2_c;
        $report->grade_2_d = $validatedData->grade_2_d;
        $report->grade_3_a = $validatedData->grade_3_a;
        $report->grade_3_b = $validatedData->grade_3_b;
        $report->grade_4_a = $validatedData->grade_4_a;
        $report->grade_4_b = $validatedData->grade_4_b;
        $report->grade_4_c = $validatedData->grade_4_c;

        $report->final_grade = round(($report->grade_1_a * 5 + $report->grade_1_b * 4 + $report->grade_1_c * 2 + $report->grade_2_a * 3 + $report->grade_2_b * 4 + $report->grade_2_c * 3 + $report->grade_2_d * 1 + $report->grade_3_a * 5 + $report->grade_3_b * 4 + $report->grade_4_a * 2 + $report->grade_4_b * 2 + $report->grade_4_c * 5) / 24, 1);
        $report->completed_hours = $validatedData->completedHours;
        $report->end_date = $validatedData->endDate;
        $report->observation = $validatedData->observation;

        $saved = $report->save();
        $log .= "\nNovos dados: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $minGrade = $report->internship->student->course_configuration->min_grade;

        if ($saved) {
            Log::info($log);
            $report->internship->state_id = ($report->final_grade >= $minGrade) ? State::FINISHED : State::INVALID;
            $report->internship->save();
        } else {
            Log::error("Erro ao salvar relatório final");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.report.index')->with($params);
    }

    public function destroyBimestral($id, DestroyBimestralReport $request)
    {
        $report = BimestralReport::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de relatório bimestral";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $report->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir relatório bimestral");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';

        return redirect()->route('coordinator.report.index')->with($params);
    }

    public function destroyFinal($id, DestroyFinalReport $request)
    {
        $report = FinalReport::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de relatório final";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $report->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir relatório final");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';

        return redirect()->route('coordinator.report.index')->with($params);
    }

    public function pdfBimonthly(Request $request)
    {
        $validatedData = (object)$request->validate([
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date'],
        ]);

        $grades = $grades ?? [1, 2, 3, 4];
        $courses = Course::findOrFail(Auth::user()->coordinator_courses_id);
        $classes = $classes ?? ['A', 'B', 'C', 'D'];

        $startDate = $validatedData->startDate != null ? Carbon::createFromFormat("!Y-m-d", $validatedData->startDate)
            : Carbon::now();

        $endDate = $validatedData->endDate != null ? Carbon::createFromFormat("!Y-m-d", $validatedData->endDate)
            : Carbon::createFromFormat("!Y-m-d", $startDate->format("Y-m-d"))->modify('+7 day');

        $students = Internship::actives()->where('state_id', '=', State::OPEN)->get()
            ->filter(function (Internship $i) use ($startDate, $endDate) {
                $reports = $i->bimestral_reports;

                foreach ($reports as $report) {
                    if ($report->date->between($startDate, $endDate)) {
                        return false;
                    }
                }

                return true;
            })->map(function ($i) {
                return $i->student;
            })->sortBy('nome');

        $data = [
            'grades' => $grades,
            'courses' => $courses,
            'classes' => $classes,
            'students' => $students,
            'endDate' => $endDate,
        ];

        $pdf = PDF::loadView('pdf.report.bimonthly', $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('relatorioBimestral.pdf');
    }

    public function pdfFinal($id)
    {
        ini_set('max_execution_time', 300);

        $report = FinalReport::findOrFail($id);
        $student = $report->internship->student;
        $sysConfig = SystemConfiguration::getCurrent();

        $data = [
            'report' => $report,
            'student' => $student,
            'sysConfig' => $sysConfig,
        ];

        $pdf = PDF::loadView('pdf.report.final', $data);
        $pdf->getDomPDF()->setHttpContext(stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Cookie: ' . implode("; ", array_map(
                        function ($k, $v) {
                            return "{$k}={$v}";
                        },
                        array_keys($_COOKIE),
                        array_values($_COOKIE)
                    )),
            ],
        ]));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('relatorioFinal.pdf');
    }

    public function pdf2Final($id)
    {
        ini_set('max_execution_time', 300);

        $report = FinalReport::findOrFail($id);
        $student = $report->internship->student;
        $sysConfig = SystemConfiguration::getCurrent();

        $data = [
            'report' => $report,
            'student' => $student,
            'sysConfig' => $sysConfig,
        ];

        $pdf = PDF::loadView('pdf.report.final_full', $data);
        $pdf->getDomPDF()->setHttpContext(stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Cookie: ' . implode("; ", array_map(
                        function ($k, $v) {
                            return "{$k}={$v}";
                        },
                        array_keys($_COOKIE),
                        array_values($_COOKIE)
                    )),
            ],
        ]));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('relatorioFinalCompleto.pdf');
    }

    private function generateApprovalNumber(Course $course)
    {
        $no = 1;
        $year = Carbon::now()->year;

        $reports = FinalReport::whereYear('date', '=', $year)->get();

        foreach ($reports as $report) {
            if ($report->internship->student->course_id == $course->id) {
                $no++;
            }
        }

        while (strlen($no) < 3) {
            $no = "0{$no}";
        }
        return "{$no}/{$year}";
    }
}
