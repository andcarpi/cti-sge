@extends('adminlte::page')

@section('title', 'Editar estágio - SGE CTI')

@section('content_header')
    <h1>Editar estágio</h1>
@stop

@section('content')

    @include('modals.coordinator.company.supervisor.new')
    @include('modals.coordinator.student.search')
    @include('modals.coordinator.cloneSchedule')

    <form class="form-horizontal" action="{{ route('coordenador.estagio.alterar', $internship->id) }}" method="post">
        @method('PUT')
        @csrf

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do estágio</h3>
            </div>

            <div class="box-body">
                <input type="hidden" id="inputHas2Schedules" name="has2Schedules"
                       value="{{ (old('has2Schedules') ?? $internship->schedule2 != null) ? '1' : '0' }}">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('ra')) has-error @endif">
                            <label for="inputRA" class="col-sm-4 control-label">RA*</label>

                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="inputRA" name="ra" placeholder="1757047"
                                           readonly data-inputmask="'mask': '9999999'"
                                           value="{{ old('ra') ?? $internship->ra }}">

                                    <div class="input-group-btn">
                                        <a href="#" data-toggle="modal" data-target="#searchStudentModal"
                                           class="btn btn-default"><i class="fa fa-search"></i></a>
                                    </div>
                                </div>

                                <span class="help-block">{{ $errors->first('ra') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('active')) has-error @endif">
                            <label for="inputActive" class="col-sm-4 control-label">Ativo*</label>

                            <div class="col-sm-8">
                                <select class="form-control selection"
                                        data-minimum-results-for-search="Infinity"
                                        id="inputActive" name="active">
                                    <option value="1" {{ (old('active') ?? $internship->active) ? 'selected' : '' }}>
                                        Sim
                                    </option>
                                    <option value="0" {{ !(old('active') ?? $internship->active) ? 'selected' : '' }}>
                                        Não
                                    </option>
                                </select>

                                <span class="help-block">{{ $errors->first('active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputStudentName" class="col-sm-2 control-label">Aluno</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control input-info" id="inputStudentName" name="student" readonly
                               value="{{ App\Models\NSac\Student::find(old('ra') ?? $internship->ra)->nome ?? '' }}"/>
                    </div>
                </div>

                <div class="form-group @if($errors->has('company')) has-error @endif">
                    <label for="inputCompany" class="col-sm-2 control-label">Empresa*</label>

                    <div class="col-sm-10">
                        <select class="selection" name="company" id="inputCompany"
                                style="width: 100%">

                            @foreach($companies as $company)

                                <option
                                    value="{{ $company->id }}" {{ (old('company') ?? $internship->company->id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->cpf_cnpj }}
                                    - {{ $company->name }} {{ $company->fantasy_name != null ? " ($company->fantasy_name)" : '' }}
                                </option>

                            @endforeach

                        </select>

                        <span class="help-block">{{ $errors->first('company') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputCompanyRepresentative" class="col-sm-2 control-label">Representante*</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control input-info" id="inputCompanyRepresentative"
                               name="representative" readonly
                               value="{{ (App\Models\Company::find(old('company')) ?? $internship->company)->representative_name }}"/>
                    </div>
                </div>

                <div class="form-group @if($errors->has('sector')) has-error @endif">
                    <label for="inputSector" class="col-sm-2 control-label">Setor*</label>

                    <div class="col-sm-10">
                        <select class="selection" name="sector" id="inputSector"
                                style="width: 100%">

                            @foreach($internship->company->sectors as $sector)

                                <option
                                    value="{{ $sector->id }}" {{ (old('sector') ?? $internship->sector->id) == $sector->id ? "selected" : "" }}>
                                    {{ $sector->name }}
                                </option>

                            @endforeach

                        </select>

                        <span class="help-block">{{ $errors->first('sector') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('startDate')) has-error @endif">
                            <label for="inputStartDate" class="col-sm-4 control-label">Data de início*</label>

                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="inputStartDate" name="startDate"
                                       value="{{ old('startDate') ?? $internship->start_date }}"/>

                                <span class="help-block">{{ $errors->first('startDate') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('endDate')) has-error @endif">
                            <label for="inputEndDate" class="col-sm-4 control-label">Data de término*</label>

                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="inputEndDate" name="endDate"
                                       value="{{ old('endDate') ?? $internship->end_date }}">

                                <span class="help-block">{{ $errors->first('endDate') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group @if($errors->has('activities')) has-error @endif">
                    <label for="inputActivities" class="col-sm-2 control-label">Atividades*</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="inputActivities" name="activities"
                                  style="resize: none"
                                  placeholder="O que o aluno fará no estágio">{{ old('activities') ?? $internship->activities }}</textarea>

                        <span class="help-block">{{ $errors->first('activities') }}</span>
                    </div>
                </div>

                <div class="form-group @if($errors->has('observation')) has-error @endif">
                    <label for="inputObservation" class="col-sm-2 control-label">Observações</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="inputObservation" name="observation"
                                  style="resize: none"
                                  placeholder="Observações adicionais">{{ old('observation') ?? $internship->observation ?? '' }}</textarea>

                        <span class="help-block">{{ $errors->first('observation') }}</span>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Salvar</button>
                <a href="{{url()->previous()}}" class="btn btn-default">Cancelar</a>
            </div>
            <!-- /.box-footer -->
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Horários</h3>
            </div>

            <div class="box-body">
                <div class="form-group">
                    <label for="inputWeekDays" class="col-sm-2 control-label">Horário*</label>

                    <div class="col-sm-10">
                        <table id="inputWeekDays" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <a href="#" data-toggle="modal" data-target="#cloneScheduleModal"
                                       onclick="schedule2 = false;">
                                        <i class="fa fa-copy"></i>
                                    </a>
                                </th>

                                <th>
                                    <label class="control-label">Seg</label>
                                </th>

                                <th>
                                    <label class="control-label">Ter</label>
                                </th>

                                <th>
                                    <label class="control-label">Qua</label>
                                </th>

                                <th>
                                    <label class="control-label">Qui</label>
                                </th>

                                <th>
                                    <label class="control-label">Sex</label>
                                </th>

                                <th>
                                    <label class="control-label">Sab</label>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td style="min-width: 100px;">
                                    <label class="control-label">Entrada</label>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('monS')) has-error @endif">
                                        <input name="monS" id="inputMonS" type="text" class="form-control input-time"
                                               value="{{ old('monS') ?? $internship->schedule->mon_s }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('tueS')) has-error @endif">
                                        <input name="tueS" id="inputTueS" type="text" class="form-control input-time"
                                               value="{{ old('tueS') ?? $internship->schedule->tue_s }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('wedS')) has-error @endif">
                                        <input name="wedS" id="inputWedS" type="text" class="form-control input-time"
                                               value="{{ old('wedS') ?? $internship->schedule->wed_s }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('thuS')) has-error @endif">
                                        <input name="thuS" id="inputThuS" type="text" class="form-control input-time"
                                               value="{{ old('thuS') ?? $internship->schedule->thu_s }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('friS')) has-error @endif">
                                        <input name="friS" id="inputFriS" type="text" class="form-control input-time"
                                               value="{{ old('friS') ?? $internship->schedule->fri_s }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('satS')) has-error @endif">
                                        <input name="satS" id="inputSatS" type="text" class="form-control input-time"
                                               value="{{ old('satS') ?? $internship->schedule->sat_s }}">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="control-label">Saída</label>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('monE')) has-error @endif">
                                        <input name="monE" id="inputMonE" type="text" class="form-control input-time"
                                               value="{{ old('monE') ?? $internship->schedule->mon_e }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('tueE')) has-error @endif">
                                        <input name="tueE" id="inputTueE" type="text" class="form-control input-time"
                                               value="{{ old('tueE') ?? $internship->schedule->tue_e }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('wedE')) has-error @endif">
                                        <input name="wedE" id="inputWedE" type="text" class="form-control input-time"
                                               value="{{ old('wedE') ?? $internship->schedule->wed_e }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('thuE')) has-error @endif">
                                        <input name="thuE" id="inputThuE" type="text" class="form-control input-time"
                                               value="{{ old('thuE') ?? $internship->schedule->thu_e }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('friE')) has-error @endif">
                                        <input name="friE" id="inputFriE" type="text" class="form-control input-time"
                                               value="{{ old('friE') ?? $internship->schedule->fri_e }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('satE')) has-error @endif">
                                        <input name="satE" id="inputSatE" type="text" class="form-control input-time"
                                               value="{{ old('satE') ?? $internship->schedule->sat_e }}">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fakeInputHas2Schedules" class="col-sm-2 control-label" style="padding-top: 0">2
                        turnos?</label>

                    <div class="col-sm-10">
                        <input type="checkbox" id="fakeInputHas2Schedules" name="fakeHas2Schedules"
                            {{ old('has2Schedules') ?? ($internship->schedule2 != null) ? 'checked="checked"' : '' }}>
                    </div>
                </div>

                <div class="form-group" id="weekDays2" style="display: none">
                    <label for="inputWeekDays2" class="col-sm-2 control-label">2º horário</label>

                    <div class="col-sm-10">
                        <table id="inputWeekDays" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <a href="#" data-toggle="modal" data-target="#cloneScheduleModal"
                                       onclick="schedule2 = true;">
                                        <i class="fa fa-copy"></i>
                                    </a>
                                </th>

                                <th>
                                    <label class="control-label">Seg</label>
                                </th>

                                <th>
                                    <label class="control-label">Ter</label>
                                </th>

                                <th>
                                    <label class="control-label">Qua</label>
                                </th>

                                <th>
                                    <label class="control-label">Qui</label>
                                </th>

                                <th>
                                    <label class="control-label">Sex</label>
                                </th>

                                <th>
                                    <label class="control-label">Sab</label>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td style="min-width: 100px;">
                                    <label class="control-label">Entrada</label>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('monS2')) has-error @endif">
                                        <input name="monS2" id="inputMonS2" type="text" class="form-control input-time"
                                               value="{{ old('monS2') ?? $internship->schedule2->mon_s ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('tueS2')) has-error @endif">
                                        <input name="tueS2" id="inputTueS2" type="text" class="form-control input-time"
                                               value="{{ old('tueS2') ?? $internship->schedule2->tue_s ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('wedS2')) has-error @endif">
                                        <input name="wedS2" id="inputWedS2" type="text" class="form-control input-time"
                                               value="{{ old('wedS2') ?? $internship->schedule2->wed_s ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('thuS2')) has-error @endif">
                                        <input name="thuS2" id="inputThuS2" type="text" class="form-control input-time"
                                               value="{{ old('thuS2') ?? $internship->schedule2->thu_s ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('friS2')) has-error @endif">
                                        <input name="friS2" id="inputFriS2" type="text" class="form-control input-time"
                                               value="{{ old('friS2') ?? $internship->schedule2->fri_s ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('satS2')) has-error @endif">
                                        <input name="satS2" id="inputSatS2" type="text" class="form-control input-time"
                                               value="{{ old('satS2') ?? $internship->schedule2->sat_s ?? '' }}">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="control-label">Saída</label>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('monE2')) has-error @endif">
                                        <input name="monE2" id="inputMonE2" type="text" class="form-control input-time"
                                               value="{{ old('monE2') ?? $internship->schedule2->mon_e ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('tueE2')) has-error @endif">
                                        <input name="tueE2" id="inputTueE2" type="text" class="form-control input-time"
                                               value="{{ old('tueE2') ?? $internship->schedule2->tue_e ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('wedE2')) has-error @endif">
                                        <input name="wedE2" id="inputWedE2" type="text" class="form-control input-time"
                                               value="{{ old('wedE2') ?? $internship->schedule2->wed_e ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('thuE2')) has-error @endif">
                                        <input name="thuE2" id="inputThuE2" type="text" class="form-control input-time"
                                               value="{{ old('thuE2') ?? $internship->schedule2->thu_e ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('friE2')) has-error @endif">
                                        <input name="friE2" id="inputFriE2" type="text" class="form-control input-time"
                                               value="{{ old('friE2') ?? $internship->schedule2->fri_e ?? '' }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group @if($errors->has('satE2')) has-error @endif">
                                        <input name="satE2" id="inputSatE2" type="text" class="form-control input-time"
                                               value="{{ old('satE2') ?? $internship->schedule2->sat_e ?? '' }}">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Salvar</button>
                <a href="{{url()->previous()}}" class="btn btn-default">Cancelar</a>
            </div>
            <!-- /.box-footer -->
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Supervisor</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group @if($errors->has('supervisor')) has-error @endif">
                            <label for="inputSupervisor" class="col-sm-4 control-label">Supervisor*</label>

                            <div class="col-sm-8">
                                <select class="selection" name="supervisor"
                                        id="inputSupervisor"
                                        style="width: 100%">

                                    @foreach($internship->company->supervisors as $supervisor)

                                        <option
                                            value="{{ $supervisor->id }}" {{ (old('supervisor') ?? $internship->supervisor->id) == $supervisor->id ? "selected" : "" }}>
                                            {{ $supervisor->name }}
                                        </option>

                                    @endforeach

                                </select>

                                <span class="help-block">{{ $errors->first('supervisor') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group pull-right">
                            <a href="#" class="btn btn-success" id="aAddSupervisor" data-toggle="modal"
                               data-target="#newInternshipSupervisorModal">Novo supervisor</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>

                        <a href="{{url()->previous()}}" class="btn btn-default">Cancelar</a>
                    </div>
                    <!-- /.box-footer -->
                </div>
            </div>

            <div class="col-sm-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados da secretaria</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group @if($errors->has('protocol')) has-error @endif">
                            <label for="inputProtocol" class="col-sm-4 control-label">Protocolo*</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputProtocol" name="protocol"
                                       placeholder="001/19" data-inputmask="'mask': '999/99'"
                                       value="{{ old('protocol') ?? $internship->protocol }}"/>

                                <span class="help-block">{{ $errors->first('protocol') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Salvar</button>
                        <a href="{{url()->previous()}}" class="btn btn-default">Cancelar</a>
                    </div>
                    <!-- /.box-footer -->
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(':input').inputmask({removeMaskOnSubmit: true});

            jQuery('.input-time').inputmask('hh:mm', {
                removeMaskOnSubmit: false
            }).parent().css('margin', '0');

            jQuery('.selection').select2({
                language: "pt-BR"
            });

            jQuery('#fakeInputHas2Schedules').on('ifChanged', function () {
                if (this.checked) {
                    jQuery('#weekDays2').css('display', 'block');
                    jQuery('#inputHas2Schedules').val(1);
                } else {
                    jQuery('#weekDays2').css('display', 'none');
                    jQuery('#inputHas2Schedules').val(0);
                }
            }).trigger('ifChanged').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            function reloadSelect() {
                jQuery('#inputSector').select2({
                    language: "pt-BR",
                    ajax: {
                        url: `/api/coordenador/empresa/${jQuery('#inputCompany').val()}/setor/`,
                        dataType: 'json',
                        method: 'GET',
                        cache: true,
                        data: function (params) {
                            return {
                                q: params.term // search term
                            };
                        },

                        processResults: function (response) {
                            sectors = [];
                            response.forEach(sector => {
                                if (sector.active) {
                                    sectors.push({id: sector.id, text: sector.name});
                                }
                            });

                            return {
                                results: sectors
                            };
                        },
                    }
                });

                jQuery('#inputSupervisor').select2({
                    language: "pt-BR",
                    ajax: {
                        url: `/api/coordenador/empresa/${jQuery('#inputCompany').val()}/supervisor/`,
                        dataType: 'json',
                        method: 'GET',
                        cache: true,
                        data: function (params) {
                            return {
                                q: params.term // search term
                            };
                        },

                        processResults: function (response) {
                            supervisors = [];
                            response.forEach(supervisor => {
                                if (supervisor.active) {
                                    supervisors.push({id: supervisor.id, text: supervisor.name});
                                }
                            });

                            return {
                                results: supervisors
                            };
                        },
                    }
                });
            }

            jQuery('#inputCompany').on('change', e => {
                jQuery.ajax({
                    url: `/api/coordenador/empresa/${jQuery('#inputCompany').val()}`,
                    dataType: 'json',
                    method: 'GET',
                    success: function (data) {
                        jQuery('#inputCompanyRepresentative').val(data.representative_name);
                    },
                    error: function () {

                    },
                });

                jQuery('#inputSector').empty();
                jQuery('#inputSupervisor').empty();

                reloadSelect();
            });

            reloadSelect();
        });
    </script>
@endsection
