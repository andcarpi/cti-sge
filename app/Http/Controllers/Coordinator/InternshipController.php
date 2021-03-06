<?php

namespace App\Http\Controllers\Coordinator;

use App\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coordinator\CancelInternship;
use App\Http\Requests\Coordinator\DestroyInternship;
use App\Http\Requests\Coordinator\ReactivateInternship;
use App\Http\Requests\Coordinator\StoreInternship;
use App\Http\Requests\Coordinator\UpdateInternship;
use App\Mail\FinalReportMail;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Schedule;
use App\Models\State;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InternshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('coordinator');
        $this->middleware('permission:internship-list');
        $this->middleware('permission:internship-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:internship-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:internship-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $courses = Auth::user()->coordinator_of;

        $internships = Internship::all()->filter(function (Internship $internship) use ($courses) {
            return $courses->contains($internship->student->course);
        });

        return view('coordinator.internship.index')->with(['internships' => $internships]);
    }

    public function create()
    {
        $companies = Company::actives()->orderBy('id')->get();
        $s = request()->s;

        return view('coordinator.internship.new')->with([
            'companies' => $companies,
            's' => $s,
            'fields' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
        ]);
    }

    public function edit($id)
    {
        $internship = Internship::findOrFail($id);
        if (!Auth::user()->coordinator_of->contains($internship->student->course)) {
            abort(404);
        }

        $companies = Company::getActives()->merge([$internship->company])->sortBy('id');

        return view('coordinator.internship.edit')->with([
            'internship' => $internship,
            'companies' => $companies,
            'fields' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
        ]);
    }

    public function show($id)
    {
        $internship = Internship::findOrFail($id);
        if (!Auth::user()->coordinator_of->contains($internship->student->course)) {
            abort(404);
        }

        return view('coordinator.internship.details')->with(['internship' => $internship]);
    }

    public function store(StoreInternship $request)
    {
        $internship = new Internship();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Novo estágio";
        $log .= "\nUsuário: {$user->name}";

        $schedule = new Schedule();

        $schedule->mon_s = $validatedData->monS;
        $schedule->mon_e = $validatedData->monE;
        $schedule->tue_s = $validatedData->tueS;
        $schedule->tue_e = $validatedData->tueE;
        $schedule->wed_s = $validatedData->wedS;
        $schedule->wed_e = $validatedData->wedE;
        $schedule->thu_s = $validatedData->thuS;
        $schedule->thu_e = $validatedData->thuE;
        $schedule->fri_s = $validatedData->friS;
        $schedule->fri_e = $validatedData->friE;
        $schedule->sat_s = $validatedData->satS;
        $schedule->sat_e = $validatedData->satE;
        $saved = $schedule->save();

        if ($validatedData->has2Schedules) {
            $schedule2 = new Schedule();

            $schedule2->mon_s = $validatedData->monS2;
            $schedule2->mon_e = $validatedData->monE2;
            $schedule2->tue_s = $validatedData->tueS2;
            $schedule2->tue_e = $validatedData->tueE2;
            $schedule2->wed_s = $validatedData->wedS2;
            $schedule2->wed_e = $validatedData->wedE2;
            $schedule2->thu_s = $validatedData->thuS2;
            $schedule2->thu_e = $validatedData->thuE2;
            $schedule2->fri_s = $validatedData->friS2;
            $schedule2->fri_e = $validatedData->friE2;
            $schedule2->sat_s = $validatedData->satS2;
            $schedule2->sat_e = $validatedData->satE2;
            $saved = $schedule2->save();

            $internship->schedule_2_id = $schedule2->id;
        }

        $internship->ra = $validatedData->ra;
        $internship->company_id = $validatedData->company;
        $internship->sector_id = $validatedData->sector;

        $coordinator = Auth::user()->coordinators->where('course_id', '=', $internship->student->course_id)->last();
        $coordinator_id = $coordinator->temporary_of->id ?? $coordinator->id;
        $internship->coordinator_id = $coordinator_id;

        $internship->schedule_id = $schedule->id;
        $internship->state_id = State::OPEN;
        $internship->supervisor_id = $validatedData->supervisor;
        $internship->start_date = $validatedData->startDate;
        $internship->end_date = $validatedData->endDate;
        $internship->protocol = $validatedData->protocol;
        $internship->activities = $validatedData->activities;
        $internship->observation = $validatedData->observation;
        $internship->active = $validatedData->active;

        $saved = $internship->save();
        $log .= "\nNovos dados: " . json_encode($internship, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar estágio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.internship.index')->with($params);
    }

    public function update($id, UpdateInternship $request)
    {
        $internship = Internship::with(['schedule', 'schedule2'])->findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de estágio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($internship, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $schedule = $internship->schedule;

        $schedule->mon_s = $validatedData->monS;
        $schedule->mon_e = $validatedData->monE;
        $schedule->tue_s = $validatedData->tueS;
        $schedule->tue_e = $validatedData->tueE;
        $schedule->wed_s = $validatedData->wedS;
        $schedule->wed_e = $validatedData->wedE;
        $schedule->thu_s = $validatedData->thuS;
        $schedule->thu_e = $validatedData->thuE;
        $schedule->fri_s = $validatedData->friS;
        $schedule->fri_e = $validatedData->friE;
        $schedule->sat_s = $validatedData->satS;
        $schedule->sat_e = $validatedData->satE;
        $saved = $schedule->save();

        if ($validatedData->has2Schedules) {
            $schedule2 = $internship->schedule2 ?? new Schedule();

            $schedule2->mon_s = $validatedData->monS2;
            $schedule2->mon_e = $validatedData->monE2;
            $schedule2->tue_s = $validatedData->tueS2;
            $schedule2->tue_e = $validatedData->tueE2;
            $schedule2->wed_s = $validatedData->wedS2;
            $schedule2->wed_e = $validatedData->wedE2;
            $schedule2->thu_s = $validatedData->thuS2;
            $schedule2->thu_e = $validatedData->thuE2;
            $schedule2->fri_s = $validatedData->friS2;
            $schedule2->fri_e = $validatedData->friE2;
            $schedule2->sat_s = $validatedData->satS2;
            $schedule2->sat_e = $validatedData->satE2;
            $saved = $schedule2->save();

            $internship->schedule_2_id = $schedule2->id;
        } else {
            $internship->schedule_2_id = null;
        }

        $internship->sector_id = $validatedData->sector;
        $internship->supervisor_id = $validatedData->supervisor;
        $internship->start_date = $validatedData->startDate;
        $internship->end_date = $validatedData->endDate;
        $internship->protocol = $validatedData->protocol;
        $internship->activities = $validatedData->activities;
        $internship->observation = $validatedData->observation;
        $internship->active = $validatedData->active;

        $saved = $internship->save();
        $log .= "\nNovos dados: " . json_encode($internship, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar estágio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.internship.index')->with($params);
    }

    public function destroy($id, DestroyInternship $request)
    {
        $internship = Internship::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de estágio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($internship, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $internship->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir estágio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';
        return redirect()->route('coordinator.internship.index')->with($params);
    }

    public function cancel($id, CancelInternship $request)
    {
        $internship = Internship::findOrFail($id);
        $validatedData = (object)$request->validated();

        $internship->state_id = State::CANCELED;
        $internship->reason_to_cancel = $validatedData->reasonToCancel;
        $internship->canceled_at = $validatedData->canceledAt;
        $saved = $internship->save();

        $user = Auth::user();
        $log = "Cancelamento de estágio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nAluno com estágio cancelado: {$internship->student->nome}";

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao cancelar estágio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.internship.index')->with($params);
    }

    public function reactivate($id, ReactivateInternship $request)
    {
        $internship = Internship::findOrFail($id);
        $validatedData = (object)$request->validated();

        $internship->state_id = State::OPEN;
        $internship->reason_to_cancel = null;
        $internship->canceled_at = null;
        $saved = $internship->save();

        $user = Auth::user();
        $log = "Reativamento de estágio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nAluno com estágio reativado: {$internship->student->nome}";

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao reativar estágio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.internship.index')->with($params);
    }

    public function checkFinishedToday()
    {
        $internships = Internship::finishedToday();

        foreach ($internships as $internship) {
            Mail::to($internship->student->email)->send(new FinalReportMail($internship->student));
        }
    }
}
