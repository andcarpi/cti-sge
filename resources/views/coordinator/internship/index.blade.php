@extends('adminlte::page')

@section('title', 'Estágios')

@section('content_header')
    <h1>Estágios</h1>
@stop

@section('content')
    @include('modals.coordinator.internship.cancel')
    @include('modals.coordinator.internship.reactivate')

    @if(session()->has('message'))
        <div class="alert {{ session('saved') ? 'alert-success' : 'alert-error' }} alert-dismissible"
             role="alert">
            {{ session()->get('message') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="box box-default">
        <div class="box-body">
            <a id="addLink" href="{{ route('coordinator.internship.new') }}"
               class="btn btn-success">Adicionar estágio</a>

            <table id="internships" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Empresa</th>
                    <th>Coordenador</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
                </thead>

                <tbody>
                @foreach($internships as $internship)
                    <tr class="{{ ($internship->needsFinalReport()) ? 'text-red' : '' }}">
                        <td>{{ $internship->ra }} - {{ $internship->student->nome }}</td>

                        <td>{{ $internship->company->formatted_cpf_cnpj }} - {{ $internship->company->name }} {{ $internship->company->fantasy_name != null ? "({$internship->company->fantasy_name})" : '' }}</td>
                        <td>{{ $internship->coordinator->user->name }}</td>
                        <td>{{ ($internship->needsFinalReport()) ? 'Requer finalização' : $internship->state->description }}</td>
                        <td>
                            <a href="{{ route('coordinator.internship.show', ['id' => $internship->id]) }}">Detalhes</a>

                            @if(\App\Auth::user()->can('internshipAmendment-list'))
                                |
                                <a href="{{ route('coordinator.internship.amendment', ['id' => $internship->id]) }}">Termos
                                    aditivos</a>
                            @endif

                            |
                            <a class="text-aqua"
                               href="{{ route('coordinator.internship.edit', ['id' => $internship->id]) }}">Editar</a>

                            @if($internship->state->id == \App\Models\State::OPEN)
                                |
                                <a href="#"
                                   onclick="internshipId('{{ $internship->id }}'); studentName('{{ $internship->student->nome }}'); return false;"
                                   data-toggle="modal" class="text-red"
                                   data-target="#internshipCancelModal">Cancelar</a>
                            @elseif($internship->state->id == \App\Models\State::CANCELED && $internship->student->internship == null)
                                |
                                <a href="#"
                                   onclick="reactivateInternshipId('{{ $internship->id }}'); reactivateStudentName('{{ $internship->student->nome }}'); return false;"
                                   data-toggle="modal" data-target="#internshipReactivateModal">Reativar</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                'Aluno-pre': function (a) {
                    return a.replace(/[\d]{7} - /g, '');
                },

                'Aluno-asc': function (a, b) {
                    return a - b;
                },

                'Aluno-desc': function (a, b) {
                    return b - a;
                },

                'Empresa-pre': function (a) {
                    return a.replace(/[\d]{2}\.[\d]{3}\.[\d]{3}\/[\d]{4}-[\d]{2} - /g, '').replace(/[\d]{3}\.[\d]{3}\.[\d]{3}-[\d]{2} - /g, '');
                },

                'Empresa-asc': function (a, b) {
                    return a - b;
                },

                'Empresa-desc': function (a, b) {
                    return b - a;
                }
            });

            let table = jQuery("#internships").DataTable({
                language: {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
                },
                responsive: true,
                lengthChange: false,
                aoColumns: [{sType: "Aluno"}, {sType: "Empresa"}, {sType: "Coordenador"}, {sType: "Estado"}, {sType: "Ações"}],
                aaSorting: [[0, "asc"]],
                buttons: [
                    {
                        extend: 'csv',
                        text: '<span class="glyphicon glyphicon-download-alt"></span> CSV',
                        charset: 'UTF-8',
                        fieldSeparator: ';',
                        bom: true,
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<span class="glyphicon glyphicon-print"></span> Imprimir',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    }
                ],
                initComplete: function () {
                    table.buttons().container().appendTo(jQuery('#internships_wrapper .col-sm-6:eq(0)'));
                    table.buttons().container().addClass('btn-group');
                    jQuery('#addLink').prependTo(table.buttons().container());
                },
            });
        });
    </script>
@endsection
