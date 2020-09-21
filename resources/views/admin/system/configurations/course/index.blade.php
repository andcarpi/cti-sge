@extends('adminlte::page')

@section('title', 'Configurações gerais de curso')

@section('content_header')
    <h1>Configurações gerais de curso</h1>
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
            <a id="addLink" href="{{ route('admin.config.course.new') }}"
               class="btn btn-success">Adicionar configuração geral</a>

            <table id="generalConfigurations" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Anos máx.</th>
                    <th>Ano mín.</th>
                    <th>Semestre mín.</th>
                    <th>Horas mín.</th>
                    <th>Meses mín.</th>
                    <th>Meses mín. (CTPS)</th>
                    <th>Nota mín.</th>
                    <th>Ações</th>
                </tr>
                </thead>

                <tbody>

                @foreach($configs as $config)
                    <tr>
                        <td>{{ $config->max_years }}</td>
                        <td>{{ $config->min_year }}</td>
                        <td>{{ $config->min_semester }}</td>
                        <td>{{ $config->min_hours }}</td>
                        <td>{{ $config->min_months }}</td>
                        <td>{{ $config->min_months_ctps }}</td>
                        <td>{{ $config->min_grade }}</td>

                        <td>
                            <a class="text-aqua"
                               href="{{ route('admin.config.course.edit', ['id' => $config->id]) }}">Editar</a>
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
            let table = jQuery("#generalConfigurations").DataTable({
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
                    table.buttons().container().appendTo(jQuery('#generalConfigurations_wrapper .col-sm-6:eq(0)'));
                    table.buttons().container().addClass('btn-group');
                    jQuery('#addLink').prependTo(table.buttons().container());
                },
            });
        });
    </script>
@endsection
