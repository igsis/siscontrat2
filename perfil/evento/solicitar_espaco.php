<?php
include "includes/menu_principal.php";
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Solicitar adição de Espaço</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Espaços</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=index"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="instituicao">Instituição: *</label>
                                    <select name="instituicao" id="instituicao" class="form-control" required>
                                        <?php
                                        geraOpcao('instituicoes');
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="local">Local: </label>
                                    <select name="local" id="local" class="form-control" required>
                                       <!-- -
/*                                            geraOpcaoPublicado('locais');
                                        */?> -->
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="sigla">Espaço: </label>
                                    <input type="text" class="form-control" id="espaco" name="espaco" required>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <button type="submit" name="cadastraEspaco" id="cadastraEspaco" class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->
    </section>
    <!-- /.content -->
</div>





<script type="text/javascript">

    const url = `<?=$url?>`;

    let instituicao = document.querySelector('#instituicao');

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();

        console.log(instituicao);

        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                    ;
                }
            })
    })

    let local = document.querySelector('#local');

    local.addEventListener('change', async e => {
        let idLocal = $('#local option:checked').val();

        fetch(`${url}?espaco_id=${idLocal}`)
            .then(response => response.json())
            .then(espacos => {
                $('#espaco option').remove();
                if (espacos.length < 1) {
                    $('#espaco').append('<option value="">Não há espaço para esse local</option>')
                        .attr('required', false)
                        .focus();
                } else {
                    $('#espaco').append('<option value="">Selecione uma opção...</option>')
                        .attr('required', true)
                        .focus();
                }

                for (const espaco of espacos) {
                    $('#espaco').append(`<option value='${espaco.id}'>${espaco.espaco}</option>`)
                }

            })
    })

    var virada = $('.virada');

    virada.on("change", function () {
        if ($('#viradaNao').is(':checked')) {
            $('#horaInicio')
                .attr('readonly', false)
                .val('');

            $('#horaFim')
                .attr('readonly', false)
                .val('');

            $('#instituicao')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#local')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#espaco')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#retiradaIngresso')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#valor_ingresso')
                .attr('readonly', false)
                .val('');
        } else {
            $('#horaInicio')
                .attr('readonly', true)
                .val('00:00');

            $('#horaFim')
                .attr('readonly', true)
                .val('00:00');

            $('#instituicao')
                .attr('readonly', true)
                .val($('option:contains("Virada Cultural")').val());

            $('#local')
                .attr('readonly', true)
                .val($('option:contains("De acordo com a programação do evento")').val());

            $('#retiradaIngresso')
                .attr('readonly', true)
                .val($('option:contains("INGRESSOS GRÁTIS")').val());

            $('#espaco')
                .attr('readonly', true);

            $('#valor_ingresso')
                .attr('readonly', true)
                .val('0,00');
        }

        getLocais(10, 626);
        getEspacos();
    });

    function getLocais(idInstituicao, selectedId) {
        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    if (selectedId == local.id) {
                        $('#local').append(`<option value='${local.id}' selected>${local.local}</option>`).focus();
                        ;
                    } else {
                        $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                        ;
                    }

                }
            })
    }

</script>


