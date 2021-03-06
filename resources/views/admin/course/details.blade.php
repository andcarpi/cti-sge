@extends('adminlte::page')

@section('title', 'Detalhes do curso')

@section('content_header')
    <h1>Detalhes do curso {{ $course->name }}
    </h1>
@stop

@section('content')

    @if(\App\Auth::user()->can('course-delete'))
        @include('modals.admin.course.delete')
    @endif

    <div class="box box-default">
        <div class="box-body">
            <div class="btn-group" style="display: inline-flex; margin: 0">
                <a href="{{ route('admin.course.edit', ['id' => $course->id]) }}"
                   class="btn btn-primary">Editar curso</a>

                <a href="{{ route('admin.course.config.index', ['id' => $course->id]) }}" class="btn btn-default">Configurações</a>

                @if($config != null && !isset($config->max_years))
                    <a href="{{ route('admin.course.config.edit', ['id' => $course->id, 'id_config' => $config->id]) }}"
                       class="btn btn-primary">Editar configuração</a>
                @else
                    <a href="{{ route('admin.course.config.new', ['id' => $course->id]) }}"
                       class="btn btn-success">Adicionar configuração</a>
                @endif

                <a href="{{ route('admin.course.coordinator', ['id' => $course->id]) }}" class="btn btn-default">Coordenadores</a>

                @if($coordinator != null)
                    <a href="{{ route('admin.coordinator.edit', ['id' => $coordinator->id]) }}"
                       class="btn btn-primary">Editar coordenador</a>
                @else
                    <a href="{{ route('admin.coordinator.new', ['c' => $course->id]) }}"
                       class="btn btn-success">Adicionar coordenador</a>
                @endif

                @if(\App\Auth::user()->can('course-delete'))
                    <a href="#"
                       onclick="deleteCourseId('{{ $course->id }}'); courseName('{{ $course->name }}'); return false;"
                       data-toggle="modal" data-target="#courseDeleteModal" class="btn btn-danger">Excluir curso</a>
                @endif
            </div>

            <h3>Dados do curso</h3>

            <dl class="row">
                <dt class="col-sm-2">Nome do curso</dt>
                <dd class="col-sm-10">{{ $course->name }}</dd>

                <dt class="col-sm-2">Cor do curso</dt>
                <dd class="col-sm-10">{{ __("colors.{$color->name}") }}</dd>

                <dt class="col-sm-2">Ativo</dt>
                <dd class="col-sm-10">{{ $course->active ? 'Sim' : 'Não' }}</dd>

                <dt class="col-sm-2">Alunos</dt>
                <dd class="col-sm-10">{{ sizeof($course->students) }}</dd>

                <dt class="col-sm-2">1º ano</dt>
                <dd class="col-sm-10">{{ sizeof($course->students->filter(function ($s) {return $s->grade == 1;})) }}</dd>

                <dt class="col-sm-2">2º ano</dt>
                <dd class="col-sm-10">{{ sizeof($course->students->filter(function ($s) {return $s->grade == 2;})) }}</dd>

                <dt class="col-sm-2">3º ano</dt>
                <dd class="col-sm-10">{{ sizeof($course->students->filter(function ($s) {return $s->grade == 3;})) }}</dd>

                <dt class="col-sm-2">Formados</dt>
                <dd class="col-sm-10">{{ sizeof($course->students->filter(function ($s) {return $s->grade == 4;})) }}</dd>
            </dl>

            <hr/>
            <h3>Configuração ativa do curso</h3>

            @if($config != null)
                <dl class="row">
                    <dt class="col-sm-2">Ano mínimo</dt>
                    <dd class="col-sm-10">{{ $config->min_year }}º ano</dd>

                    <dt class="col-sm-2">Semestre mínimo</dt>
                    <dd class="col-sm-10">{{ $config->min_semester }}º semestre</dd>

                    <dt class="col-sm-2">Horas mínimas</dt>
                    <dd class="col-sm-10">{{ $config->min_hours }}</dd>

                    <dt class="col-sm-2">Meses mínimos</dt>
                    <dd class="col-sm-10">{{ $config->min_months }}</dd>

                    <dt class="col-sm-2">Meses mínimos (CTPS)</dt>
                    <dd class="col-sm-10">{{ $config->min_months_ctps }}</dd>

                    <dt class="col-sm-2">Nota mínima</dt>
                    <dd class="col-sm-10">{{ $config->min_grade }}</dd>

                    <dt class="col-sm-2">Ativo desde</dt>
                    <dd class="col-sm-10">{{ date_format($config->created_at, "d/m/Y") }} {{ isset($config->max_years) ? ' (Configuração geral)' : '' }}</dd>
                </dl>
            @else
                <p>Não há configurações para este curso!</p>
            @endif

            <hr/>
            <h3>Coordenador do curso</h3>

            @if($coordinator != null)
                <dl class="row">
                    <dt class="col-sm-2">Nome</dt>
                    <dd class="col-sm-10">{{ $coordinator->user->name }}</dd>

                    <dt class="col-sm-2">Início da vigência</dt>
                    <dd class="col-sm-10">{{ $coordinator->start_date->format("d/m/Y") }}</dd>

                    @if($coordinator->end_date != null)
                        <dt class="col-sm-2">Fim da vigência</dt>
                        <dd class="col-sm-10">{{ $coordinator->end_date->format("d/m/Y") }}</dd>
                    @endif
                </dl>
            @else
                <p>Não há um coordenador ativo para este curso!</p>
            @endif

        </div>
        <!-- /.box-body -->
    </div>
@endsection

@section('js')
    <script type="text/javascript">

    </script>
@endsection
