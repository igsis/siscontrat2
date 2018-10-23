<?php

    $con = bancoMysqli();
    include "includes/menu_interno.php";

    if (isset($_POST['idAtracao'])){
        $idAtracao = $_POST['idAtracao'];
    }

    if (isset($_POST['cadastra']) || isset($_POST['edita'])){
        $idAtracao = $_POST['idAtracao'];
        $certificado = $_POST['certificado'];
        $vagas = $_POST['vagas'];
        $publicoAlvo = $_POST['publicoAlvo'];
        $material = $_POST['material'];
        $inscricao = $_POST['inscricao'];
        $valorHora = $_POST['valorHora'];
        $venda = $_POST['venda'];
        $dataDivulgacao = $_POST['dataDivulgacao'];
        $cargaHoraria = $_POST['cargaHoraria'];
    }

    if (isset($_POST['cadastra'])){

        $sql = "INSERT INTO `oficinas` ";
        $sql .= "(atracao_id, certificado, vagas, publico_alvo, material, inscricao, valor_hora, venda, data_divulgacao, carga_horaria) ";
        $sql .= "VALUES('$idAtracao','$certificado','$vagas','$publicoAlvo','$material','$inscricao','$valorHora','$venda','$dataDivulgacao','$cargaHoraria')";

        if (mysqli_query($con,$sql)){
            $resultado = mensagem("success", "Oficina cadastrada!");
        }else{
            $erro = die(mysqli_error($con));
            $resultado = mensagem("danger", "[COD2] Erro ao gravar! Tente novamente.\n".$erro);
        }

    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Evento</h2>
        <?php
            echo $resultado;
        ?>
        ?>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtor do Evento</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="?perfil=evento&p=oficina_edita" method="post" role="form">
                        <div class="box-body">
                            <input type="hidden" name="idAtracao" value="<?= $idAtracao?>">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="certificado">Certificado: *</label>
                                    <input type="number" class="form-control" name="certificado" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="vagas">Vagas: *</label>
                                    <input type="number" class="form-control" name="vagas" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="publicoAlvo">Público Alvo: *</label>
                                <textarea name="publicoAlvo" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="material">Material: </label>
                                <textarea name="material" id="" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inscricao">Inscrição: *</label>
                                    <input type="number" class="form-control" name="inscricao">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="valorHora">Valor Hora: *</label>
                                    <input type="text" class="form-control" name="valorHora">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="venda">Venda: *</label>
                                    <input type="number" class="form-control" name="venda">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="dataDivulgacao">Data de Divulgação: *</label>
                                    <input type="text" class="form-control" name="dataDivulgacao">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cargaHoraria">Carga  Horaria: *</label>
                                    <input type="number" class="form-control" name="cargaHoraria">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
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