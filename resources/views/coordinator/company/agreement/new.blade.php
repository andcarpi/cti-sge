@extends('adminlte::page')

@section('title', 'Novo convênio')

@section('content_header')
    <h1>Adicionar novo conênio</h1>
@stop

@section('content')
    <form class="form-horizontal" action="{{ route('coordinator.company.agreement.store') }}" method="post">
        @csrf

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do convênio</h3>
            </div>

            <div class="box-body">
                <div class="form-group @if($errors->has('company')) has-error @endif">
                    <label for="inputCompany" class="col-sm-2 control-label">Empresa*</label>

                    <div class="col-sm-10">
                        <select class="selection" name="company" id="inputCompany"
                                style="width: 100%">

                            @foreach($companies as $company)
                                <option
                                    value="{{ $company->id }}" {{ (old('company') ?? $c) == $company->id ? 'selected=selected' : '' }}>
                                    {{ $company->formatted_cpf_cnpj }} - {{ $company->name }} {{ $company->fantasy_name != null ? " ($company->fantasy_name)" : '' }}
                                </option>
                            @endforeach

                        </select>

                        <span class="help-block">{{ $errors->first('company') }}</span>
                    </div>
                </div>

                <div class="form-group @if($errors->has('startDate')) has-error @endif">
                    <label for="inputStartDate" class="col-sm-2 control-label">Data de início*</label>

                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="inputStartDate" name="startDate"
                               value="{{ old('startDate') ?? date("Y-m-d") }}"/>

                        <span class="help-block">{{ $errors->first('startDate') }}</span>
                    </div>
                </div>

                <div class="form-group @if($errors->has('observation')) has-error @endif">
                    <label for="inputObservation" class="col-sm-2 control-label">Observações</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="inputObservation" name="observation"
                                  style="resize: none"
                                  placeholder="Observações adicionais">{{ old('observation') ?? '' }}</textarea>

                        <span class="help-block">{{ $errors->first('observation') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right">Adicionar</button>

                <input type="hidden" id="inputPrevious" name="previous"
                       value="{{ old('previous') ?? url()->previous() }}">
                <a href="{{ old('previous') ?? url()->previous() }}" class="btn btn-default">Cancelar</a>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.selection').select2({
                language: "pt-BR"
            });
        });
    </script>
@endsection
