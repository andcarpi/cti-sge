<?php

namespace App\Http\Controllers\Coordinator;

use App\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coordinator\CancelAgreement;
use App\Http\Requests\Coordinator\DestroyAgreement;
use App\Http\Requests\Coordinator\ReactivateAgreement;
use App\Http\Requests\Coordinator\StoreAgreement;
use App\Http\Requests\Coordinator\UpdateAgreement;
use App\Models\Agreement;
use App\Models\Company;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
    public function __construct()
    {
        $this->middleware('coordinator');
        $this->middleware('permission:companyAgreement-list');
        $this->middleware('permission:companyAgreement-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:companyAgreement-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:companyAgreement-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $agreements = Agreement::all();
        return view('coordinator.company.agreement.index')->with(['agreements' => $agreements]);
    }

    public function indexByCompany($id)
    {
        $company = Company::findOrFail($id);
        $agreements = $company->agreements;
        return view('coordinator.company.agreement.index')->with(['company' => $company, 'agreements' => $agreements]);
    }

    public function create()
    {
        $companies = Company::actives()->orderBy('id')->get();
        $c = request()->c;
        return view('coordinator.company.agreement.new')->with(['companies' => $companies, 'c' => $c]);
    }

    public function edit($id)
    {
        $agreement = Agreement::findOrFail($id);
        return view('coordinator.company.agreement.edit')->with(['agreement' => $agreement]);
    }

    public function store(StoreAgreement $request)
    {
        $agreement = new Agreement();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Novo convênio";
        $log .= "\nUsuário: {$user->name}";

        $agreement->company_id = $validatedData->company;
        $agreement->start_date = $validatedData->startDate;
        $agreement->end_date = SystemConfiguration::getAgreementExpiration($agreement->start_date);
        $agreement->active = true;
        $agreement->observation = $validatedData->observation;

        $saved = $agreement->save();
        $log .= "\nNovos dados: " . json_encode($agreement, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
            $agreement->createUser();
        } else {
            Log::error("Erro ao salvar convênio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.company.agreement.index')->with($params);
    }

    public function update($id, UpdateAgreement $request)
    {
        $agreement = Agreement::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de convênio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($agreement, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $agreement->start_date = $validatedData->startDate;
        $agreement->end_date = SystemConfiguration::getAgreementExpiration($agreement->start_date);
        $agreement->observation = $validatedData->observation;

        $saved = $agreement->save();
        $log .= "\nNovos dados: " . json_encode($agreement, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);

            if (!$agreement->company->user) {
                $agreement->createUser();
            }
        } else {
            Log::error("Erro ao salvar convênio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.company.agreement.index')->with($params);
    }

    public function destroy($id, DestroyAgreement $request)
    {
        $agreement = Agreement::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de convênio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($agreement, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $agreement->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir convênio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';
        return redirect()->route('coordinator.company.agreement.index')->with($params);
    }

    public function cancel($id, CancelAgreement $request)
    {
        $agreement = Agreement::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Cancelamento de convênio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nEmpresa com convênio cancelado: " . $agreement->company->name;

        $agreement->active = false;

        $saved = $agreement->save();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao cancelar convênio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.company.agreement.index')->with($params);
    }

    public function reactivate($id, ReactivateAgreement $request)
    {
        $agreement = Agreement::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Reativação de convênio";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nEmpresa com convênio reativado: " . $agreement->company->name;

        $agreement->active = true;

        $saved = $agreement->save();

        if ($saved) {
            Log::info($log);

            if (!$agreement->company->user) {
                $agreement->createUser();
            }
        } else {
            Log::error("Erro ao reativar convênio");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('coordinator.company.agreement.index')->with($params);
    }

    public function deleteUsers()
    {
        $agreements = Agreement::expiredToday();

        foreach ($agreements as $agreement) {
            $agreement->deleteUser();
        }
    }
}
