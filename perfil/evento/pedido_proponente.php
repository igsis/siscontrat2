<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

if (isset($_POST['trocaPf']) || isset($_POST['trocaPj'])) {
    if (isset($_POST['trocaPf'])) {
        $_SESSION['idPedido'] = $_POST['idPedido'];
        $idPedido = $_SESSION['idPedido'];
        $idPessoa = $_POST['idPf'] ?? $_POST['idPessoa'];
        $trocaPf = $con->query("UPDATE pedidos SET pessoa_fisica_id = $idPessoa WHERE id = $idPedido AND origem_tipo_id = 1");
        if ($trocaPf) {
            $deletaPj = $con->query("UPDATE pedidos SET pessoa_juridica_id = null, pessoa_tipo_id = 1 WHERE id = $idPedido AND origem_tipo_id = 1");
            $mensagem = mensagem('success', 'Proponente trocado com sucesso!');
        } else {
            $mensagem = mensagem('danger', 'Erro ao trocar proponente! Tente novamente.');
        }
        $pedido = recuperaDados("pedidos", "id", $idPedido);
    }
    if (isset($_POST['trocaPj'])) {
        $_SESSION['idPedido'] = $_POST['idPedido'];
        $idPedido = $_SESSION['idPedido'];
        $idPessoa = $_POST['idPj'] ?? $_POST['idPessoa'];
        $trocaPj = $con->query("UPDATE pedidos SET pessoa_juridica_id = $idPessoa WHERE id = $idPedido AND origem_tipo_id = 1");
        if ($trocaPj) {
            $deletaPf = $con->query("UPDATE pedidos SET pessoa_fisica_id = null, pessoa_tipo_id = 2 WHERE id = $idPedido AND origem_tipo_id = 1");
            $mensagem = mensagem('success', 'Proponente trocado com sucesso!');
        } else {
            $mensagem = mensagem('danger', 'Erro ao trocar proponente! Tente novamente.');
        }
        $pedido = recuperaDados("pedidos", "id", $idPedido);
    }
}


$evento = recuperaDados("eventos", "id", $idEvento);
$sql = "SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
$query = $con->query($sql);
$pedido = $query->fetch_assoc();

if ($pedido['pessoa_tipo_id'] == 2) {
    $pj = recuperaDados("pessoa_juridicas", "id", $pedido['pessoa_juridica_id']);
    $proponente = $pj['razao_social'];
    $idProponente = $pj['id'];
    $link_edita = "?perfil=evento&p=pj_edita";
} else {
    $pf = recuperaDados("pessoa_fisicas", "id", $pedido['pessoa_fisica_id']);
    $proponente = $pf['nome'];
    $idProponente = $pf['id'];
    $link_edita = "?perfil=evento&p=pf_edita";
}

$tipoPessoa = $pedido['pessoa_tipo_id'];

include "includes/menu_interno.php";
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
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro de Proponente</h3>
                    </div>

                    <div class="row" align="center">
                        <?= $mensagem ?? "" ?>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <div class="jumbotrom">
                                    <label for="proponente">Proponente</label>
                                    <input type="text" id="proponente" name="proponente"
                                           class="form-control" disabled
                                           value="<?= $proponente ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-2"><label><br></label>
                                <form method="POST" action="<?= $link_edita ?>" role="form">
                                    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                    <button type="submit" name="editProponente" class="btn btn-primary btn-block">
                                        Editar Proponente
                                    </button>
                                </form>
                            </div>
                            <div class="form-group col-md-2"><label><br></label>
                                <form method="POST" action="?perfil=evento&p=troca_proponente"
                                      role="form">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                    <button type="submit" name="trocaProponente" class="btn btn-primary btn-block">
                                        Trocar de Proponente
                                    </button>
                                </form>
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
    </section>
    <!-- /.content -->
</div>
