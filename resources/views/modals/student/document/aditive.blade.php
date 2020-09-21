<div class="modal fade" id="documentAditive" tabindex="-1" role="dialog" aria-labelledby="documentAditive"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="documentAditiveTitle">Termo aditivo</h4>
            </div>

            <form id="aditiveForm" action="{{ route('student.document.amendment') }}" target="_blank"
                  class="form-horizontal" method="get">
                <div class="modal-body">
                    <p>Qual tipo de termo aditivo você precisa?</p>

                    <p><input type="radio" class="radio" name="id" value="0" checked> Adiantar ou prorrogar o tempo de
                        estágio.</p>
                    <p><input type="radio" class="radio" name="id" value="1"> Altere os dias e horários que você fará
                        estágio.</p>
                    <p><input type="radio" class="radio" name="id" value="2"> Outros motivos.</p>
                </div>

                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Gerar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    @parent

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#aditiveForm').on('submit', () => {
                jQuery('#documentAditive').modal('hide');
            });

            jQuery('.radio').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        })
    </script>
@endsection
