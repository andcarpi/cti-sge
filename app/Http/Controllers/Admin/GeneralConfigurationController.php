<?php

namespace App\Http\Controllers\Admin;

use App\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyGeneralConfiguration;
use App\Http\Requests\Admin\StoreGeneralConfiguration;
use App\Http\Requests\Admin\UpdateGeneralConfiguration;
use App\Models\GeneralConfiguration;
use Illuminate\Support\Facades\Log;

class GeneralConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:generalConfiguration-list');
        $this->middleware('permission:generalConfiguration-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:generalConfiguration-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:generalConfiguration-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $configs = GeneralConfiguration::all();
        return view('admin.system.configurations.course.index')->with(['configs' => $configs]);
    }

    public function create()
    {
        return view('admin.system.configurations.course.new');
    }

    public function edit($id)
    {
        $config = GeneralConfiguration::findOrFail($id);
        return view('admin.system.configurations.course.edit')->with(['config' => $config]);
    }

    public function store(StoreGeneralConfiguration $request)
    {
        $config = new GeneralConfiguration();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Nova configuração geral de curso";
        $log .= "\nUsuário: {$user->name}";

        $config->max_years = $validatedData->maxYears;
        $config->min_year = $validatedData->minYear;
        $config->min_semester = $validatedData->minSemester;
        $config->min_hours = $validatedData->minHours;
        $config->min_months = $validatedData->minMonths;
        $config->min_months_ctps = $validatedData->minMonthsCTPS;
        $config->min_grade = $validatedData->minGrade;

        $saved = $config->save();
        $log .= "\nNovos dados: " . json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar configuração geral de curso");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('admin.config.course.index')->with($params);
    }

    public function update($id, UpdateGeneralConfiguration $request)
    {
        $config = GeneralConfiguration::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de configuração geral de curso";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $config->max_years = $validatedData->maxYears;
        $config->min_year = $validatedData->minYear;
        $config->min_semester = $validatedData->minSemester;
        $config->min_hours = $validatedData->minHours;
        $config->min_months = $validatedData->minMonths;
        $config->min_months_ctps = $validatedData->minMonthsCTPS;
        $config->min_grade = $validatedData->minGrade;

        $saved = $config->save();
        $log .= "\nNovos dados: " . json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar configuração geral de curso");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('admin.config.course.index')->with($params);
    }

    public function destroy($id, DestroyGeneralConfiguration $request)
    {
        $config = GeneralConfiguration::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de configuração geral de curso";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $config->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir configuração geral de curso");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';
        return redirect()->route('admin.config.course.index')->with($params);
    }
}
