<div class="modal fade" id="newJobCompanyModal" tabindex="-1" role="dialog" aria-labelledby="newJobCompanyModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="formJobCompany" class="form-horizontal"
                  action="{{ route('api.coordinator.job.company.store') }}" method="post">
                @csrf

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="deleteModalTitle">Adicionar nova empresa (CTPS)</h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="inputCompanyPj" name="pj" value="0">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputCompanyCpfCnpj" class="col-sm-4 control-label">CPF / CNPJ*</label>

                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <span id="CpfCnpjOption"></span>
                                                <span class="fa fa-caret-down"></span>
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="pj(true); return false;">CNPJ</a></li>
                                                <li><a href="#" onclick="pj(false); return false;">CPF</a></li>
                                            </ul>
                                        </div>

                                        <input type="text" class="form-control" id="inputCompanyCpfCnpj"
                                               name="cpfCnpj"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputCompanyActive" class="col-sm-4 control-label">Ativo*</label>

                                <div class="col-sm-8">
                                    <select class="form-control selection" data-minimum-results-for-search="Infinity"
                                            id="inputCompanyActive" name="active">
                                        <option value="1" selected="selected">Sim</option>
                                        <option value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCompanyIE" class="col-sm-2 control-label">Inscrição estadual</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputCompanyIE" name="ie"
                                   placeholder="02.232.3355-6" data-inputmask="'mask': '99999999999999999999'"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCompanyName" class="col-sm-2 control-label">Razão social*</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputCompanyName" name="companyName"
                                   placeholder="MSTech"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCompanyFantasyName" class="col-sm-2 control-label">Nome fantasia</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputCompanyFantasyName" name="fantasyName"
                                   placeholder=""/>
                        </div>
                    </div>

                    <hr/>
                    <h4>Representante</h4>

                    <div class="form-group">
                        <label for="inputCompanyRepresentativeName" class="col-sm-2 control-label">Nome*</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputCompanyRepresentativeName"
                                   name="representativeName" placeholder=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCompanyRepresentativeRole" class="col-sm-2 control-label">Cargo*</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputCompanyRepresentativeRole"
                                   name="representativeRole" placeholder="Administração, desenvolvedor..."/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary pull-right">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    @parent

    <script type="text/javascript">
        function pj(isPj) {
            if (isPj) {
                jQuery('#CpfCnpjOption').text('CNPJ');

                jQuery("input[id*='inputCompanyCpfCnpj']").inputmask({
                    mask: '99.999.999/9999-99',
                    removeMaskOnSubmit: true
                });

                jQuery('#inputCompanyPj').val(1);
            } else {
                jQuery('#CpfCnpjOption').text('CPF');

                jQuery("input[id*='inputCompanyCpfCnpj']").inputmask({
                    mask: '999.999.999-99',
                    removeMaskOnSubmit: true
                });

                jQuery('#inputCompanyPj').val(0);
            }
        }

        jQuery(document).ready(function () {
            jQuery('#formJobCompany').submit(e => {
                e.preventDefault();

                jQuery.ajax({
                    url: '{{ route('api.coordinator.job.company.store') }}',
                    data: {
                        cpfCnpj: jQuery('#inputCompanyCpfCnpj').inputmask('unmaskedvalue'),
                        ie: jQuery('#inputCompanyIE').inputmask('unmaskedvalue'),
                        pj: jQuery('#inputCompanyPj').val(),
                        companyName: jQuery('#inputCompanyName').val(),
                        fantasyName: jQuery('#inputCompanyFantasyName').val(),
                        representativeName: jQuery('#inputCompanyRepresentativeName').val(),
                        representativeRole: jQuery('#inputCompanyRepresentativeRole').val(),
                        active: parseInt(jQuery('#inputCompanyActive').select2('val')),
                    },
                    method: 'POST',
                    success: function (data) {
                        let formatted_cpf_cnpj;
                        if (company.pj) {
                            let p1 = company.cpf_cnpj.substring(0, 2);
                            let p2 = company.cpf_cnpj.substring(2, 5);
                            let p3 = company.cpf_cnpj.substring(5, 8);
                            let p4 = company.cpf_cnpj.substring(8, 12);
                            let p5 = company.cpf_cnpj.substring(12, 14);
                            formatted_cpf_cnpj = `${p1}.${p2}.${p3}/${p4}-${p5}`;
                        } else {
                            let p1 = company.cpf_cnpj.substring(0, 3);
                            let p2 = company.cpf_cnpj.substring(3, 6);
                            let p3 = company.cpf_cnpj.substring(6, 9);
                            let p4 = company.cpf_cnpj.substring(9, 11);
                            formatted_cpf_cnpj = `${p1}.${p2}.${p3}-${p4}`;
                        }

                        let text = `${formatted_cpf_cnpj} - ${company.name}`;
                        if (company.fantasy_name !== null) {
                            text = `${text} (${company.fantasy_name})`;
                        }
                        jQuery('#inputCompany').append(new Option(text, `${data.id}`, false, true));

                        jQuery('#inputCompanyCpfCnpj').val('');
                        jQuery('#inputCompanyIE').val('');
                        jQuery('#inputCompanyPj').val('');
                        jQuery('#inputCompanyName').val('');
                        jQuery('#inputCompanyFantasyName').val('');
                        jQuery('#inputCompanyRepresentativeName').val('');
                        jQuery('#inputCompanyRepresentativeRole').val('');
                        jQuery('#inputCompanyActive').select2('val', '1');

                        jQuery('#newJobCompanyModal').modal('hide');
                    },

                    error: function (data) {
                        let errors = [];
                        for (let key in data.responseJSON.errors) {
                            data.responseJSON.errors[key].forEach(e => {
                                errors.push(e);
                            });
                        }

                        alert(errors.join('\n'));
                    }
                });
            });

            function loadCnpj() {
                jQuery.ajax({
                    url: `{{ config('app.url') }}/api/coordenador/trabalho/empresa?cpf_cnpj=${jQuery('#inputCompanyCpfCnpj').inputmask('unmaskedvalue')}`,
                    dataType: 'json',
                    type: 'GET',
                    success: function (companies) {
                        if (companies.length > 0) {
                            jQuery("#companyErrorModal").modal({
                                backdrop: "static",
                                keyboard: false,
                                show: true
                            });

                            jQuery('#inputCompanyCpfCnpj').val('');
                        } else if (jQuery('#inputCompanyPj').val() === '1') {
                            jQuery("#cnpjLoadingModal").modal({
                                backdrop: "static",
                                keyboard: false,
                                show: true
                            });

                            jQuery.ajax({
                                url: `{{ config('app.url') }}/api/external/cnpj/${jQuery('#inputCompanyCpfCnpj').inputmask('unmaskedvalue')}`,
                                dataType: 'json',
                                type: 'GET',
                                success: function (company) {
                                    jQuery("#cnpjLoadingModal").modal("hide");

                                    if (company.error) {
                                        jQuery("#cnpjErrorModal").modal({
                                            backdrop: "static",
                                            keyboard: false,
                                            show: true
                                        });

                                        company.name = '';
                                        company.fantasyName = '';
                                    }

                                    jQuery('#inputCompanyName').val(company.name);
                                    jQuery('#inputCompanyFantasyName').val(company.fantasyName);
                                },

                                error: function () {
                                    jQuery("#cnpjLoadingModal").modal("hide");

                                    jQuery("#cnpjErrorModal").modal({
                                        backdrop: "static",
                                        keyboard: false,
                                        show: true
                                    });
                                }
                            });
                        }
                    },

                    error: function () {

                    }
                });
            }

            jQuery('#inputCompanyCpfCnpj').blur(() => {
                if (jQuery('#inputCompanyCpfCnpj').val() !== "") {
                    loadCnpj();
                }
            });

            pj(1);
        });
    </script>
@endsection
