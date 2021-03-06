@extends('adminlte::page')

@section('title', 'Coordenadores')

@section('content_header')
    <h1>Coordenadores @if(isset($course)) de {{ $course->name }} @endif</h1>
@stop

@section('content')
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
            <a id="addLink"
               href="{{ (isset($course)) ? route('admin.coordinator.new', ['c' => $course->id]) : route('admin.coordinator.new') }}"
               class="btn btn-success">Adicionar coordenador</a>

            <table id="coordinators" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Nome</th>
                    @if(!isset($course))
                        <th>Curso</th>
                    @endif
                    <th>Início</th>
                    <th>Término</th>
                    <th>Ações</th>
                </tr>
                </thead>

                <tbody>
                @foreach($coordinators as $coordinator)
                    <tr>
                        <td>{{ $coordinator->user->name }}</td>
                        @if(!isset($course))
                            <td>{{ $coordinator->course->name }}</td>
                        @endif
                        <td>{{ $coordinator->start_date->format("d/m/Y") }}</td>
                        <td>{{ $coordinator->end_date != null ? $coordinator->end_date->format("d/m/Y") : 'Indefinido' }}</td>

                        <td>
                            <a class="text-aqua"
                               href="{{ route('admin.coordinator.edit', ['id' => $coordinator->id]) }}">Editar</a>
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
            let table = jQuery("#coordinators").DataTable({
                language: {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
                },
                responsive: true,
                lengthChange: false,
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
                    table.buttons().container().appendTo(jQuery('#coordinators_wrapper .col-sm-6:eq(0)'));
                    table.buttons().container().addClass('btn-group');
                    jQuery('#addLink').prependTo(table.buttons().container());
                },
            });
        });
    </script>
@endsection
