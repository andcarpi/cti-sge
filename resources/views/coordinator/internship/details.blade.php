@extends('adminlte::page')

@section('title', 'Detalhes do estágio - SGE CTI')

@section('content_header')
    <h1>Detalhes do estágio</h1>
@stop

@section('content')
    @include('modals.coordinator.internship.cancel')
    @include('modals.coordinator.internship.reactivate')

    <div class="box box-default">
        <div class="box-body">
            <div class="btn-group" style="display: inline-flex; margin: 0">
                <a href="{{ route('coordenador.estagio.editar', $internship->id) }}"
                   class="btn btn-primary">Editar estágio</a>

                <a href="{{ route('coordenador.estagio.aditivo', ['id' => $internship->id]) }}"
                   class="btn btn-default">Termos aditivos</a>

                @if($internship->state->id == \App\Models\State::OPEN)

                    <a href="{{ route('coordenador.relatorio.bimestral.novo', ['i' => $internship->id]) }}"
                       class="btn btn-success">Adicionar relatório bimestral</a>

                    <a href="{{ route('coordenador.relatorio.final.novo', ['i' => $internship->id]) }}"
                       class="btn btn-success">Adicionar relatório final</a>

                    <a href="#"
                       onclick="internshipId('{{ $internship->id }}'); studentName('{{ $internship->student->nome }}'); return false;"
                       data-toggle="modal" class="btn btn-danger" data-target="#internshipCancelModal">Cancelar</a>

                @elseif($internship->state->id == \App\Models\State::CANCELED && $internship->student->internship == null)

                    <a href="#"
                       onclick="reactivateInternshipId('{{ $internship->id }}'); reactivateStudentName('{{ $internship->student->nome }}'); return false;"
                       data-toggle="modal" class="btn btn-default" data-target="#internshipReactivateModal">Reativar</a>

                @endif
            </div>

            <h3>Dados do aluno</h3>

            <dl class="row">
                <dt class="col-sm-2">RA</dt>
                <dd class="col-sm-10">{{ $internship->student->matricula }}</dd>

                <dt class="col-sm-2">Nome</dt>
                <dd class="col-sm-10">{{ $internship->student->nome }}</dd>

                <dt class="col-sm-2">Curso</dt>
                <dd class="col-sm-10">{{ $internship->student->course->name }}</dd>

                <dt class="col-sm-2">Turma</dt>
                <dd class="col-sm-10">{{ $internship->student->turma }} ({{ $internship->student->turma_ano }})</dd>

                <dt class="col-sm-2">Ano de matrícula</dt>
                <dd class="col-sm-10">{{ $internship->student->year }}</dd>

                <dt class="col-sm-2">Email</dt>
                <dd class="col-sm-10">{{ $internship->student->email }}</dd>

                <dt class="col-sm-2">Email institucional</dt>
                <dd class="col-sm-10">{{ $internship->student->email2 }}</dd>
            </dl>

            <hr/>
            <h3>Dados do estágio</h3>

            <dl class="row">
                <dt class="col-sm-2">CPF / CNPJ da empresa</dt>
                <dd class="col-sm-10">{{ $internship->company->cpf_cnpj }}</dd>

                <dt class="col-sm-2">Empresa</dt>
                <dd class="col-sm-10">{{ $internship->company->name }}</dd>

                <dt class="col-sm-2">Nome fantasia</dt>
                <dd class="col-sm-10">{{ $internship->company->fantasy_name ?? '(Não informado)' }}</dd>

                <dt class="col-sm-2">Setor</dt>
                <dd class="col-sm-10">{{ $internship->sector->name }}</dd>

                <dt class="col-sm-2">Supervisor</dt>
                <dd class="col-sm-10">{{ $internship->supervisor->name }}</dd>

                <dt class="col-sm-2">Data de início</dt>
                <dd class="col-sm-10">{{ $internship->start_date->format("d/m/Y") }}</dd>

                @if($internship->final_report == null)

                    <dt class="col-sm-2">Data de término</dt>
                    <dd class="col-sm-10">
                        {{ $internship->a_end_date->format('d/m/Y') }}
                        @if($internship->a_end_date != $internship->end_date) * @endif
                    </dd>

                    <dt class="col-sm-2">Horas estimadas</dt>
                    <dd class="col-sm-10">{{ $internship->estimated_hours }}</dd>

                @else

                    <dt class="col-sm-2">Data de término</dt>
                    <dd class="col-sm-10">{{ $internship->final_report->end_date->format("d/m/Y") }}</dd>

                    <dt class="col-sm-2">Horas concluídas</dt>
                    <dd class="col-sm-10">{{ $internship->final_report->completed_hours }}</dd>

                    <dt class="col-sm-2">Nota final</dt>
                    <dd class="col-sm-10">{{ $internship->final_report->final_grade }}</dd>

                    <dt class="col-sm-2">Número de aprovação</dt>
                    <dd class="col-sm-10">{{ $internship->final_report->approval_number }}</dd>

                @endif

                <dt class="col-sm-2">Estado</dt>
                <dd class="col-sm-10">{{ $internship->state->description }}</dd>

                @if($internship->state_id == 3)

                    <dt class="col-sm-2">Motivo do cancelamento</dt>
                    <dd class="col-sm-10">{{ $internship->reason_to_cancel }}</dd>

                    <dt class="col-sm-2">Data do cancelamento</dt>
                    <dd class="col-sm-10">{{ $internship->canceled_at->format("d/m/Y") }}</dd>

                @endif
            </dl>
        </div>
        <!-- /.box-body -->
    </div>
@endsection

@section('js')
    <script type="text/javascript">

    </script>
@endsection