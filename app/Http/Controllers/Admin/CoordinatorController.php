<?php

namespace App\Http\Controllers\Admin;

use App\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyCoordinator;
use App\Http\Requests\Admin\StoreCoordinator;
use App\Http\Requests\Admin\UpdateCoordinator;
use App\Models\Coordinator;
use App\Models\Course;
use App\Models\User;
use App\Notifications\WebNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coordinator-list');
        $this->middleware('permission:coordinator-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:coordinator-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:coordinator-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $coordinators = Coordinator::all();

        return view('admin.coordinator.index')->with(['coordinators' => $coordinators]);
    }

    public function indexByCourse($id)
    {
        $course = Course::findOrFail($id);
        $coordinators = $course->coordinators;

        return view('admin.coordinator.index')->with(['coordinators' => $coordinators, 'course' => $course]);
    }

    public function create()
    {
        $courses = Course::all()->where('active', '=', true)->sortBy('id');
        $users = User::whereHas("roles", function ($q) {
            $q->where("name", "teacher");
        })->get()->sortBy('id');

        $c = request()->c;

        return view('admin.coordinator.new')->with(["courses" => $courses, "users" => $users, 'c' => $c]);
    }

    public function edit($id)
    {
        $coordinator = Coordinator::findOrFail($id);
        $courses = Course::all()->where('active', '=', true)->merge([$coordinator->course])->sortBy('id');
        $users = User::whereHas("roles", function ($q) {
            $q->where("name", "teacher");
        })->get()->sortBy('id');

        return view('admin.coordinator.edit')->with(['coordinator' => $coordinator, 'users' => $users, 'courses' => $courses]);
    }

    public function store(StoreCoordinator $request)
    {
        $coordinator = new Coordinator();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Novo coordenador";
        $log .= "\nUsuário: {$user->name}";

        $coordinator->user_id = $validatedData->user;
        $coordinator->course_id = $validatedData->course;
        $coordinator->temp_of = null;
        if ($validatedData->tempOf > 0) {
            $coordinator->temp_of = $validatedData->tempOf;
        }

        $coordinator->start_date = $validatedData->startDate;
        $coordinator->end_date = $validatedData->endDate;

        $saved = $coordinator->save();
        $log .= "\nNovos dados: " . json_encode($coordinator, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            if ($coordinator->temporary_of == null && sizeof($coordinator->course->non_temp_coordinators) > 1) {
                $c = $coordinator->course->non_temp_coordinators->where('id', '<>', $coordinator->id)->last();
                $c->end_date = Carbon::today();
                $c->save();
            }

            Log::info($log);
            $cName = $coordinator->course->name;
            $notification = $coordinator->temporary_of == null ? new WebNotification(['description' => "Coordenadoria de $cName", 'text' => "Você agora é coordenador de $cName.", 'icon' => 'black-tie'])
                : new WebNotification(['description' => "Coordenadoria de $cName", 'text' => "Você agora é coordenador temporário de $cName.", 'icon' => 'black-tie']);
            $coordinator->user->notify($notification);
        } else {
            Log::error("Erro ao salvar coordenador");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('admin.coordenador.index')->with($params);
    }

    public function update($id, UpdateCoordinator $request)
    {
        $coordinator = Coordinator::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de coordenador";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($coordinator, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $coordinator->user_id = $validatedData->user;
        $coordinator->course_id = $validatedData->course;
        $coordinator->temp_of = null;
        if ($validatedData->tempOf > 0) {
            $coordinator->temp_of = $validatedData->tempOf;
        }

        $coordinator->start_date = $validatedData->startDate;
        $coordinator->end_date = $validatedData->endDate;

        $saved = $coordinator->save();
        $log .= "\nNovos dados: " . json_encode($coordinator, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);

            $cName = $coordinator->course->name;
            $user = Auth::user();
            $endDate = ($coordinator->end_date != null) ? $coordinator->end_date->format("d/m/Y") : 'Indeterminado';
            $notification = new WebNotification(['description' => "Coordenadoria de $cName", 'text' => "O usuário $user->name alterou sua data de vigência para $endDate.", 'icon' => 'black-tie']);
            $coordinator->user->notify($notification);
        } else {
            Log::error("Erro ao salvar coordenador");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('admin.coordenador.index')->with($params);
    }

    public function destroy($id, DestroyCoordinator $request)
    {
        $coordinator = Coordinator::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de coordenador";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($coordinator, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $coordinator->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir coordenador");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';
        return redirect()->route('admin.coordenador.index')->with($params);
    }

    public function checkCoordinators()
    {
        $coordinators = Coordinator::expiredToday();

        /* @var $coordinator Coordinator */
        foreach ($coordinators as $coordinator) {
            $cName = $coordinator->course->name;
            $notification = new WebNotification(['description' => "Coordenadoria de $cName", 'text' => "Seu cargo de coordenador expirou.", 'icon' => 'calendar']);
            $coordinator->user->notify($notification);
        }

        $coordinators = Coordinator::actives();

        /* @var $coordinator Coordinator */
        foreach ($coordinators as $coordinator) {
            $endDate = $coordinator->end_date;
            $max = Carbon::now()->modify('-30 day');
            if ($endDate < $max) {
                $period = $max->diff($endDate)->format("%a");
                $cName = $coordinator->course->name;
                $notification = new WebNotification(['description' => "Coordenadoria de $cName", 'text' => "Seu cargo de coordenador expira em $period dias.", 'icon' => 'calendar']);
                $coordinator->user->notify($notification);
            }
        }
    }
}
