<?php
include "includes/menu_interno.php";

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados("eventos", "id", $idEvento);
$sql = "SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
$query = $con->query($sql);
$pedido = $query->fetch_assoc();
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
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Verba:</strong> Aeoo</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Parcelas:</strong> Parcela Única</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Valor Total:</strong> R$ 80.000,00</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>
                                                    <strong>Forma de Pagamento:</strong> Teste
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>
                                                    <strong>Justificativa: </strong> Teste 2
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>
                                                    <strong>Observação: </strong> Teste 3
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box box-info">
                                    <div class="box-header">
                                        <h3 class="box-title">Cadastro do Proponente</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Razão Social: </strong>Aquela Empresa</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>CNPJ: </strong>12345678945</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Representante Fiscal: </strong> Aeoo De Souza</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Representante Fiscal 2: </strong> Aeoo Soares</p>
                                            </div>
                                        </div>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>1º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>2º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>3º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>4º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                        </div>
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
                                                <p><strong>1º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>2º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>3º Tópico: </strong> Aeoo 123</p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><strong>4º Tópico: </strong> Aeoo 123</p>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>Equipmento X: </strong> Aeoo 123</p>
                                            </div>
                                        </div>
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