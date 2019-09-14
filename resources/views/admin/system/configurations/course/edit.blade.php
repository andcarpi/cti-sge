@extends('adminlte::page')

@section('title', 'Editar configurações gerais de curso - SGE CTI')

@section('content_header')
    <h1>Editar configurações gerais de curso</h1>
@stop

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Dados da configuração</h3>
        </div>

        <form class="form-horizontal" action="{{ route('admin.configuracao.curso.alterar', $config->id) }}"
              method="post">
            @method('PUT')
            @csrf

            <div class="box-body">
                <div class="form-group @if($errors->has('maxYears')) has-error @endif">
                    <label for="inputMaxYears" class="col-sm-2 control-label">Anos máximos*</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMaxYears"
                               name="maxYears" placeholder="5" value="{{ old('maxYears') ?? $config->max_years }}"/>

                        <span class="help-block">{{ $errors->first('maxYears') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('minYear')) has-error @endif">
                            <label for="inputMinYear" class="col-sm-4 control-label">Ano mínimo*</label>

                            <div class="col-sm-8">
                                <select class="form-control selection" data-minimum-results-for-search="Infinity"
                                        id="inputMinYear" name="minYear">
                                    <option value="1"
                                        {{ (old('minYear') ?? $config->min_year) == 1 ? 'selected=selected' : '' }}>1º ano
                                    </option>
                                    <option value="2"
                                        {{ (old('minYear') ?? $config->min_year) == 2 ? 'selected=selected' : '' }}>2º ano
                                    </option>
                                    <option value="3"
                                        {{ (old('minYear') ?? $config->min_year) == 3 ? 'selected=selected' : '' }}>3º ano
                                    </option>
                                </select>

                                <span class="help-block">{{ $errors->first('minYear') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('minSemester')) has-error @endif">
                            <label for="inputMinSemester" class="col-sm-4 control-label">Semestre mínimo*</label>

                            <div class="col-sm-8">
                                <select class="form-control selection" data-minimum-results-for-search="Infinity"
                                        id="inputMinSemester" name="minSemester">
                                    <option value="1"
                                        {{ (old('minSemester') ?? $config->min_semester) == 1 ? 'selected=selected' : '' }}>1º semestre
                                    </option>
                                    <option value="2"
                                        {{ (old('minSemester') ?? $config->min_semester) == 2 ? 'selected=selected' : '' }}>2º semestre
                                    </option>
                                </select>

                                <span class="help-block">{{ $errors->first('minSemester') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('minHour')) has-error @endif">
                            <label for="inputMinHour" class="col-sm-4 control-label">Horas mínimas*</label>

                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="inputMinHour" name="minHour"
                                       placeholder="420" value="{{ old('minHour') ?? $config->min_hours }}"/>

                                <span class="help-block">{{ $errors->first('minHour') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('minMonth')) has-error @endif">
                            <label for="inputMinMonth" class="col-sm-4 control-label">Meses mínimos*</label>

                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="inputMinMonth" name="minMonth"
                                       placeholder="6" value="{{ old('minMonth') ?? $config->min_months }}"/>

                                <span class="help-block">{{ $errors->first('minMonth') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('minMonthCTPS')) has-error @endif">
                            <label for="inputMinMonthCTPS" class="col-sm-4 control-label">Meses mínimos (CTPS)*</label>

                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="inputMinMonth" name="minMonthCTPS"
                                       placeholder="6" value="{{ old('minMonthCTPS') ?? $config->min_months_ctps }}"/>

                                <span class="help-block">{{ $errors->first('minMonthCTPS') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('minGrade')) has-error @endif">
                            <label for="inputMinGrade" class="col-sm-4 control-label">Nota mínima*</label>

                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="inputMinGrade" name="minGrade"
                                       placeholder="10" step="0.5" value="{{ old('minGrade') ?? $config->min_grade }}"/>

                                <span class="help-block">{{ $errors->first('minGrade') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Salvar</button>

                <input type="hidden" id="inputPrevious" name="previous"
                       value="{{ old('previous') ?? url()->previous() }}">
                <a href="{{ old('previous') ?? url()->previous() }}" class="btn btn-default">Cancelar</a>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.selection').select2({
                language: "pt-BR"
            });

            jQuery(':input').inputmask({removeMaskOnSubmit: true});
        });
    </script>
@endsection

