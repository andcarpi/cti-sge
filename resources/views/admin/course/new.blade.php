@extends('adminlte::page')

@section('title', 'Novo curso')

@section('content_header')
    <h1>Adicionar novo curso</h1>
@stop

@section('content')
    <form class="form-horizontal" action="{{ route('admin.course.store') }}" method="post">
        @csrf

        <input type="hidden" id="inputHasConfig" name="hasConfig" value="{{ old('hasConfig') ?? 0 }}">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do curso</h3>
            </div>

            <div class="box-body">
                <div class="form-group @if($errors->has('name')) has-error @endif">
                    <label for="inputName" class="col-sm-2 control-label">Nome*</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="Informática"
                               value="{{ old('name') ?? '' }}"/>

                        <span class="help-block">{{ $errors->first('name') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('color')) has-error @endif">
                            <label for="inputColor" class="col-sm-4 control-label">Cor*</label>

                            <div class="col-sm-8">
                                <select class="form-control selection" id="inputColor" name="color">

                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}"
                                            {{ (old('color') ?? -1) == $color->id ? 'selected=selected' : '' }}>
                                            {{ __("colors.{$color->name}") }}
                                        </option>
                                    @endforeach

                                </select>

                                <span class="help-block">{{ $errors->first('color') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('active')) has-error @endif">
                            <label for="inputActive" class="col-sm-4 control-label">Ativo*</label>

                            <div class="col-sm-8">
                                <select class="form-control selection" data-minimum-results-for-search="Infinity"
                                        id="inputActive" name="active">
                                    <option value="1" {{ (old('active') ?? 1) ? 'selected=selected' : '' }}>Sim</option>
                                    <option value="0" {{ !(old('active') ?? 1) ? 'selected=selected' : '' }}>Não
                                    </option>
                                </select>
                            </div>

                            <span class="help-block">{{ $errors->first('active') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <input type="checkbox" id="fakeInputHasConfig" name="fakeHasConfig"
                        {{ (old('hasConfig') ?? 0) ? 'checked="checked"' : '' }}/>

                    Adicionar configuração?
                </h3>
            </div>

            <div id="div-config" style="display: none">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group @if($errors->has('minYear')) has-error @endif">
                                <label for="inputMinYear" class="col-sm-4 control-label">Ano mínimo*</label>

                                <div class="col-sm-8">
                                    <select class="form-control selection" data-minimum-results-for-search="Infinity"
                                            id="inputMinYear" name="minYear">
                                        <option value="1"
                                            {{ (old('minYear') ?? 1) == 1 ? 'selected=selected' : '' }}>1º ano
                                        </option>
                                        <option value="2"
                                            {{ (old('minYear') ?? 1) == 2 ? 'selected=selected' : '' }}>2º ano
                                        </option>
                                        <option value="3"
                                            {{ (old('minYear') ?? 1) == 3 ? 'selected=selected' : '' }}>3º ano
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
                                            {{ (old('minSemester') ?? 1) == 1 ? 'selected=selected' : '' }}>1º semestre
                                        </option>
                                        <option value="2"
                                            {{ (old('minSemester') ?? 1) == 2 ? 'selected=selected' : '' }}>2º semestre
                                        </option>
                                    </select>

                                    <span class="help-block">{{ $errors->first('minSemester') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group @if($errors->has('minHours')) has-error @endif">
                                <label for="inputMinHours" class="col-sm-4 control-label">Horas mínimas*</label>

                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputMinHours" name="minHours"
                                           placeholder="420" value="{{ old('minHours') ?? '' }}"/>

                                    <span class="help-block">{{ $errors->first('minHours') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group @if($errors->has('minMonths')) has-error @endif">
                                <label for="inputMinMonths" class="col-sm-4 control-label">Meses mínimos*</label>

                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputMinMonths" name="minMonths"
                                           placeholder="6" value="{{ old('minMonths') ?? '' }}"/>

                                    <span class="help-block">{{ $errors->first('minMonths') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group @if($errors->has('minMonthsCTPS')) has-error @endif">
                                <label for="inputMinMonthsCTPS" class="col-sm-4 control-label">Meses mínimos (CTPS)*</label>

                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputMinMonthsCTPS" name="minMonthsCTPS"
                                           placeholder="6" value="{{ old('minMonthsCTPS') ?? '' }}"/>

                                    <span class="help-block">{{ $errors->first('minMonthsCTPS') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group @if($errors->has('minGrade')) has-error @endif">
                                <label for="inputMinGrade" class="col-sm-4 control-label">Nota mínima*</label>

                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputMinGrade" name="minGrade"
                                           placeholder="10" step="0.5" value="{{ old('minGrade') ?? '' }}"/>

                                    <span class="help-block">{{ $errors->first('minGrade') }}</span>
                                </div>
                            </div>
                        </div>
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

            jQuery('#fakeInputHasConfig').on('ifChanged', function () {
                jQuery('#div-config').toggle(this.checked);
                jQuery('#inputHasConfig').val(Number(this.checked));
            }).trigger('ifChanged').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
