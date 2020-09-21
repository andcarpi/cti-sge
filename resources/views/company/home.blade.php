<div class="col-sm-4">
    <div class="small-box bg-blue">
        <div class="inner">
            <h3>{{ sizeof($proposals) == 0 ? 'Nenhuma' : sizeof($proposals) }}</h3>

            <p>{{ sizeof($proposals) < 2 ? "Proposta pendente" : "Propostas pendentes" }}.</p>
        </div>
        <div class="icon">
            <i class="fa fa-calendar-minus-o"></i>
        </div>
        <a href="{{ route('company.proposal.index', ['s' => 'p']) }}" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<div class="col-sm-4">
    <div class="small-box bg-green">
        <div class="inner">
            <h3>{{ sizeof($proposalsApproved) == 0 ? 'Nenhuma' : sizeof($proposalsApproved) }}</h3>

            <p>{{ sizeof($proposalsApproved) < 2 ? "Proposta aprovada" : "Propostas aprovadas" }}.</p>
        </div>
        <div class="icon">
            <i class="fa fa-check"></i>
        </div>
        <a href="{{ route('company.proposal.index', ['s' => 'a']) }}" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<div class="col-sm-4">
    <div class="small-box bg-red">
        <div class="inner">
            <h3>{{ sizeof($propalsRejected) == 0 ? 'Nenhuma' : sizeof($propalsRejected) }}</h3>

            <p>{{ sizeof($propalsRejected) < 2 ? "Proposta requer alteração" : "Propostas requerem alterações" }}.</p>
        </div>
        <div class="icon">
            <i class="fa fa-times"></i>
        </div>
        <a href="{{ route('company.proposal.index', ['s' => 'c']) }}" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
