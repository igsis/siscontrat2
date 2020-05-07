<?php
include "includes/menu_interno.php";

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados("eventos", "id", $idEvento);
$sql = "SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
$query = $con->query($sql);
$pedido = $query->fetch_assoc();

if($pedido['pessoa_tipo_id'] == 1) {
    $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
} else {
    $proponente = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
    $representante1 = recuperaDados('representante_legais', 'id', $proponente['representante_legal1_id']);
    $representante2 = recuperaDados('representante_legais', 'id', $proponente['representante_legal2_id']);
}

$valoresPorEquipamento = $con->query("SELECT * FROM valor_equipamentos WHERE pedido_id = '{$pedido['id']}'")->fetch_all(MYSQLI_ASSOC);
$parecer = recuperaDados('parecer_artisticos', 'pedido_id', $pedido['id']);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Dados do Pedido de Contratação</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-danger btn-sm" id="excluiPedido"
                                    data-toggle="modal" data-target="#exclusao" name="excluiPedido"
                                    data-id="<?=$pedido['id']?>">
                                <span class='glyphicon glyphicon-trash'></span> Excluir Pedido</button>
                        </div>
                    </div>

                    <div class="row" align="center">
                        <?= $mensagem ?? "" ?>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Detalhes de Parcelas</h3>
                                    </div>
                                    <div class="box-body">
                                        <?php if (($pedido['numero_parcelas'] != null) || ($pedido['verba_id']) != null): ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong>Verba:</strong> <?=$pedido['verba_id']?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Parcelas:</strong> <?=$pedido['numero_parcelas']?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Valor Total:</strong> <?=$pedido['valor_total']?></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>
                                                        <strong>Forma de Pagamento:</strong> <?=$pedido['forma_pagamento']?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>
                                                        <strong>Justificativa: </strong> <?=$pedido['justificativa']?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>
                                                        <strong>Observação: </strong> <?=$pedido['observacao']?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    Detalhes da parcela não cadastrado
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box box-info">
                                    <div class="box-header">
                                        <h3 class="box-title">Cadastro do Proponente</h3>
                                    </div>
                                    <div class="box-body">
                                        <?php if (($pedido['pessoa_juridica_id'] == null) && ($pedido['pessoa_fisica_id'] == null)): ?>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    Proponente não cadastrado
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php if ($pedido['pessoa_tipo_id'] == 1): ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Nome: </strong><?=$proponente['nome']?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>CPF: </strong><?=$proponente['cpf']?></p>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Razão Social: </strong><?=$proponente['razao_social']?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>CNPJ: </strong><?=$proponente['cnpj']?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Representante Fiscal: </strong><?=$representante1['nome']?></p>
                                                    </div>
                                                    <?php if ($representante2 != null): ?>
                                                        <div class="col-md-6">
                                                            <p><strong>Representante Fiscal 2: </strong><?=$representante2['nome']?></p>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-warning">
                                    <div class="box-header">
                                        <h3 class="box-title">Parecer Artístico</h3>
                                    </div>
                                    <div class="box-body">
                                        <?php if ($parecer != null): ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><strong>1º Tópico: </strong> <?= $parecer['topico1'] ?></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p><strong>2º Tópico: </strong> <?= $parecer['topico2'] ?></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p><strong>3º Tópico: </strong> <?= $parecer['topico3'] ?></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p><strong>4º Tópico: </strong> <?= $parecer['topico4'] ?></p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    Pareceres artístico não cadastrados
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title">Anexos do Pedido</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>Anexo 1: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>Anexo 2: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>Anexo 3: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>Anexo 4: </strong> Aeoo 123</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box box-danger">
                                    <div class="box-header">
                                        <h3 class="box-title">Valores por Equipamento</h3>
                                    </div>
                                    <div class="box-body">
                                        <?php if ($valoresPorEquipamento != null): ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><strong>Equipmento X: </strong> Aeoo 123</p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    Valores por equipamento não cadastrados
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        Footer
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!--.modal-->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este pedido?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=evento&p=pedido" method="post">
                            <input type="hidden" name="idPedido" id="idPedido" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Excluir">
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idPedido').attr('value', `${id}`);
    })
</script>