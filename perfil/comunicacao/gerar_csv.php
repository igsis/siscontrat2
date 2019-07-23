<?php
include "includes/menu.php";
$con = bancoMysqli();

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START ACCORDION-->
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <h2 class="page-header">Comunicação - Gerar CSV</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="box-group" id="gerarCSV">
                            <form action="" class="form-group">
                                <div class="row text-center">
                                    <div class="col-md-offset-3 col-md-3">
                                        <label for="data_inicio"> Data início </label><br>
                                        <input type="text" name="data_inicio" class="form-control datepicker">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="data_encerramento"> Data encerramento</label>
                                        <input type="text" name="data_encerramento" class="form-control datepicker" id="encerramento">
                                    </div>
                                </div>
                                <br>
                                <div class="row text-center">
                                    <div class="col-md-5" style="margin-left: 30%">
                                        <label for="instituicao">Instituição</label><br>
                                        <select name="instituicao" id="instituicao" class="form-control" onchange="insti_local()">
                                            <option value="">Selecione...</option>
                                            <?php geraOpcao('instituicoes', '') ?>
                                        </select>
                                    </div>
                                    <div class="col-md-5" id="local"  style="margin-left: 30%; display: none">
                                        <br>
                                        <label for="SelectLocal">Local</label>
                                        <select name="SelectLocal" id="SelectLocal" class="form-control">
                                            <option value="">Selecione...</option>
                                            <?php geraOpcao('locais', '') ?>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <div class="row" align="center">
                                <?php if (isset($mensagem)) {
                                    echo $mensagem;
                                }; ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
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

<script>
    $(function() {
        $(".datepicker").datepicker( {
            maxDate: 0
        });
    });

    function insti_local() {
        const urlModal = `<?=$url?>`;

        var idInstituicaoModal = $('#instituicao').val();
        $('#local').show();

        $.post(urlModal, {
            instituicao_id: idInstituicaoModal,
        })
            .done(function (data) {
                $('#SelectLocal option').remove();
                $('#SelectLocal').append('<option value="">Selecione uma opção...</option>');

                for (let local of data) {
                    $('#SelectLocal').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                }
            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });

    }



</script>