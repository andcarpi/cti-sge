<p>Atualmente, você é coordenador de {{ $strCourses }}.</p>

<div class="row">
    <div class="col-sm-3">
        <a href="{{ route('coordinator.company.index') }}">
            <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-building"></i>
            </span>

                <div class="info-box-content">
                    <span class="info-box-text">Empresas</span>
                    <span class="info-box-number">{{ $companyCount }}</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="{{ route('coordinator.internship.index') }}">
            <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-id-badge"></i>
            </span>

                <div class="info-box-content">
                    <span class="info-box-text">Estágios</span>
                    <span class="info-box-number">{{ $internshipCount }}</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="{{ route('coordinator.job.index') }}">
            <div class="info-box">
            <span class="info-box-icon bg-gray">
                <i class="fa fa-briefcase"></i>
            </span>

                <div class="info-box-content">
                    <span class="info-box-text">Trabalhos</span>
                    <span class="info-box-number">{{ $jobCount }}</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="{{ route('coordinator.report.index') }}">
            <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-book"></i>
            </span>

                <div class="info-box-content">
                    <span class="info-box-text">Relatórios</span>
                    <span class="info-box-number">{{ $reportCount }}</span>
                </div>
            </div>
        </a>
    </div>
</div>

@if(sizeof($requiringFinish) > 0)
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Estágios finalizados há +20 dias (sem relatório final)</h3>
        </div>

        <div class="box-body no-padding">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Empresa</th>
                    <th>Ações</th>
                </tr>
                </thead>

                <tbody>
                @foreach($requiringFinish as $internship)
                    <tr>
                        <td>{{ $internship->ra }} - {{ $internship->student->nome }}</td>
                        <td>{{ $internship->company->formatted_cpf_cnpj }} - {{ $internship->company->name }} {{ $internship->company->fantasy_name != null ? "({$internship->company->fantasy_name})" : '' }}</td>
                        <td>
                            <a href="{{ route('coordinator.internship.show', ['id' => $internship->id]) }}">Detalhes</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@if(sizeof($proposals) > 0)
    @include('modals.coordinator.proposal.approve', ['redirect_to' => 'home'])
    @include('modals.coordinator.proposal.reject', ['redirect_to' => 'home'])
    @include('modals.coordinator.proposal.delete', ['redirect_to' => 'home'])

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Propostas de estágio pendentes</h3>
        </div>

        <div class="box-body no-padding">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col">Empresa</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Data limite</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>

                <tbody>
                @foreach($proposals as $proposal)
                    <tr>
                        <td>{{ $proposal->company->name }}</td>
                        <td>{{ $proposal->description }}</td>
                        <td>{{ $proposal->deadline->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('coordinator.proposal.show', ['id' => $proposal->id]) }}">Detalhes</a>
                            |
                            <a href="#" onclick="approveProposalId('{{ $proposal->id }}'); return false;"
                               data-toggle="modal" class="text-green" data-target="#proposalApproveModal">Aprovar</a>
                            |
                            <a href="#" onclick="rejectProposalId('{{ $proposal->id }}'); return false;"
                               data-toggle="modal" class="text-red" data-target="#proposalRejectModal">Recusar</a>
                            |
                            <a href="#" onclick="deleteProposalId('{{ $proposal->id }}'); return false;"
                               data-toggle="modal" class="text-red" data-target="#proposalDeleteModal">Excluir</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
