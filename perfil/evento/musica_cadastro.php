<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idAtracao = $_POST['idAtracao'];

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Especificidade</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=musica_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="genero">Gênero *</label><br/>
                                    <input class="form-control" type="text" name="genero" size="30" required>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="venda">Venda de material?</label> <br>
                                    <label><input type="radio" name="venda" class="venda" id="nao" value="0" checked> Não </label>
                                    <label><input type="radio" name="venda" class="venda" id="sim" value="1"> Sim </label>
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="descricao">Descrição</label>
                                    <input type="text" class="form-control" name="descricao" id="descricao" maxlength="255" readonly>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
                                <button type="submit" name="cadastra" class="btn btn-info pull-right">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var venda = $('.venda');
    venda.on("change", function () {
        if($('#sim').is(':checked')){
            $('#descricao')
                .attr('readonly', false)
                .val('');
        }else{
            $('#descricao')
                .attr('readonly', true)
                .val('');
        }
    });
</script>