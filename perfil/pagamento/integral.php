<?php
$con = bancoMysqli();

$idPedido = $_POST['idPedido'];
$botao = false;

if(isset($_POST['operador'])){
    $operador = $_POST['operador'];
    $data_kit_pagamento = $_POST['data_kit_pagamento'];
    $sql = "UPDATE pedidos SET operador_pagamento_id = '$operador', data_kit_pagamento = '$data_kit_pagamento' WHERE id = '$idPedido'";
    $cadastra = $con->query($sql);
    if($cadastra){
        $botao = true;
        $existeEtapa = $con->query("SELECT pedido_id, data_pagamento FROM pedido_etapas WHERE pedido_id = '$idPedido'")->fetch_assoc();
        $now = dataHoraNow();
        if($existeEtapa != NULL && $existeEtapa['data_pagamento'] == "0000-00-00 00:00:00"){
            $con->query("UPDATE pedido_etapas SET data_pagamento = '$now' WHERE pedido_id = '$idPedido'");
        }
        if($existeEtapa == NULL){
            $con->query("INSERT INTO pedido_etapas (pedido_id,data_pagamento) VALUES ('$idPedido','$now')");
        }
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
    } else{
        $mensagem = mensagem("danger", "Erro ao cadastrar.");
    }
}

if(isset($_POST['cadastrar'])){
    $cadastra = $con->query("UPDATE pedidos SET status_pedido_id = 19 WHERE id = '$idPedido'");
    if($cadastra){
        $botao = true;
        $existeEtapa = $con->query("SELECT pedido_id, data_pagamento FROM pedido_etapas WHERE pedido_id = '$idPedido'")->fetch_assoc();
        $now = dataHoraNow();
        if($existeEtapa != NULL && $existeEtapa['data_pagamento'] == "0000-00-00 00:00:00"){
            $con->query("UPDATE pedido_etapas SET data_pagamento = '$now' WHERE pedido_id = '$idPedido'");
        }
        if($existeEtapa == NULL){
            $con->query("INSERT INTO pedido_etapas (pedido_id,data_pagamento) VALUES ('$idPedido','$now')");
        }
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
    } else{
        $mensagem = mensagem("danger", "Erro ao cadastrar.");
    }
}

if(isset($_POST['concluir'])){
    $cadastra = $con->query("UPDATE pedidos SET status_pedido_id = 21 WHERE id = '$idPedido'");
    if($cadastra){
        $botao = true;
        $mensagem = mensagem("success", "Evento concluído com sucesso!");
        echo "<meta http-equiv='refresh' content='3;url=?perfil=pagamento' />";
    } else{
        $mensagem = mensagem("danger", "Erro ao concluir evento.");
    }
}

$sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.forma_pagamento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento, uf.nome_completo AS fiscal, us.nome_completo AS suplente, p.operador_pagamento_id
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    LEFT JOIN usuarios uf on e.fiscal_id = uf.id
    LEFT JOIN usuarios us on e.suplente_id = us.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND p.id = '$idPedido'";
$pedido = $con->query($sql)->fetch_array();
if ($pedido['pessoa_tipo_id'] == 2) {
    $idPj = $pedido['pessoa_juridica_id'];
    $proponente = $con->query("SELECT razao_social FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_assoc()['razao_social'];
} else {
    $idPf = $pedido['pessoa_fisica_id'];
    $proponente = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_assoc()['nome'];
}

$idUser = $_SESSION['usuario_id_s'];
$testaAcesso = $con->query("SELECT * FROM usuario_pagamentos WHERE usuario_id = '$idUser'");
if($testaAcesso->num_rows == 0){
    $acesso = 0;
}else{
    $acessoArray = mysqli_fetch_array($testaAcesso);
    $acesso = $acessoArray['nivel_acesso'];
}

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Pagamento</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Cadastro de Pagamento</h2>
            </div>
            <div class="row" align="center">
                <?= $mensagem ?? NULL; ?>
            </div>
            <div class="box-body">
                <?php
                if($acesso == 1 || $acesso == 2) {
                    ?>
                    <div class="row">
                        <form action="#" method="post" role="form">
                            <div class="col-md-5 from-group">
                                <label for="operador">Operador</label>
                                <select name="operador" id="operador" class="form-control" required>
                                    <option value="">Selecione um operador</option>
                                    <?php
                                    geraOpcao('usuarios u INNER JOIN usuario_pagamentos up on up.usuario_id = u.id', $pedido['operador_pagamento_id']);
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="data_kit_pagamento">Data de Entrega do Kit de Pagamentos:</label>
                                <input type="date" class="form-control" name="data_kit_pagamento"
                                       id="data_kit_pagamento" value="<?= $pedido['data_kit_pagamento'] ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">Gravar</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <label>Protocolo:</label> <?= $pedido['protocolo'] ?>
                    </div>
                    <div class="col-md-6">
                        <label>Número de processo:</label> <?= $pedido['numero_processo'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Proponente:</label> <?= $proponente ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Nome do evento:</label> <?= $pedido['nome_evento'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Local:</label> <?php retornaLocal($pedido['id']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Período:</label> <?= retornaPeriodoNovo($pedido['id'], 'ocorrencias') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Forma de pagamento:</label> <?= $pedido['forma_pagamento'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Valor:</label> R$ <?= dinheiroParaBr($pedido['valor_total']) ?>
                    </div>
                    <div class="col-md-4">
                        <label>Fiscal:</label> <?= $pedido['fiscal'] ?>
                    </div>
                    <div class="col-md-4">
                        <label>Suplente:</label> <?= $pedido['suplente'] ?>
                    </div>
                </div>

                <br>

            </div>
            <div class="box-footer">
                <form action="#" method="post" role="form">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <button type="submit" class="btn btn-warning pull-left" name="concluir">Concluir evento</button>
                    <button type="submit" class="btn btn-primary pull-right" name="cadastrar">Gravar</button>
                </form>
            </div>
        </div>

        <?php
        if ($botao){
            $server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2/pdf/";
            if ($pedido['pessoa_tipo_id'] == 1 && $pedido['pessoa_tipo_id'] != NULL) {
                $link1 = $server . "pagamento_integral_pf.php";
                $link2 = $server . "pagamento_parcelado_pf.php";
                $link3 = $server . "recibo_pagamento.php";
                $link4 = $server . "ateste_documentacao.php";
                $link5 = $server . "confirmacao_servico.php";
                ?>
                <div class="box">
                    <div class="box-header">
                        <h2 class="box-title">Modelos de impressão</h2>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <form action="<?= $link1 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" style="width:175px" name="idPedido" value="<?= $idPedido ?>">Pedido Integral
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="<?= $link2 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" style="width:175px" name="idPedido" value="<?= $idPedido ?>">Pedido parcelado
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="<?= $link3 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" style="width:175px" name="idPedido" value="<?= $idPedido ?>">Recibo Integral
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link4 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary pull-left btn-block" style="width:250px" name="idPedido" value="<?= $idPedido ?>">Ateste (Documentação)
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link5 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary pull-right btn-block" name="idPedido" style="width:253px" value="<?= $idPedido ?>">Confirmação de serviço
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else if($pedido['pessoa_tipo_id'] == 2 && $pedido['pessoa_tipo_id'] != NULL){
                $link11 = $server . "pagamento_integral_pj.php";
                $link12 = $server . "recibo_pagamento.php";
                $link13 = $server . "ateste_documentacao.php";
                $link14 = $server . "confirmacao_servico.php";
                $link15 = $server . "minuta.php";
                $link16 = $server . "emissao_nf.php";
                $link17 = $server . "declaracao_simples.php";
                $link18 = $server . "declaracao_semFinsLucrativos.php";
                $link19 = $server . "email_empresas.php";
                ?>
                <div class="box">
                    <div class="box-header">
                        <h2 class="box-title">Modelos de impressão</h2>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <form action="<?= $link11 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" style="width:240px" name="idPedido" value="<?= $idPedido ?>">Pedido Integral</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link12 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" style="width:240px" name="idPedido" value="<?= $idPedido ?>">Recibo pagamento</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link13 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:240px" value="<?= $idPedido ?>">Ateste (Documentação)</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link14 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:240px" value="<?= $idPedido ?>">Confirmação de serviço</button>
                                </form>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <form action="<?= $link15 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" style="width:240px" name="idPedido" value="<?= $idPedido ?>">Minuta acima de R$ 176 mil</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link16 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:240px" value="<?= $idPedido ?>">Instruções para emissão de NF</button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="<?= $link17 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:230px" value="<?= $idPedido ?>">Declaração Simples</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="<?= $link18 ?>" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block pull-right" name="idPedido" style="width:81%; font-size:95%" value="<?= $idPedido ?>">Declaração de associação sem fins lucrativos</button>
                                </form>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <form action="<?= $link19 ?>?modelo=empresas" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:240px" value="<?= $idPedido ?>">Email Empresas</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="<?= $link19 ?>?modelo=cooperativas" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:240px" value="<?= $idPedido ?>">Email Cooperativas
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="<?= $link19 ?>?modelo=associacoes" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block" name="idPedido" style="width:240px" value="<?= $idPedido ?>">Email Associações e institutos
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="<?= $link19 ?>?modelo=''" method="post" target="_blank" role="form">
                                    <button type="submit" class="btn btn-primary btn-block pull-right" name="idPedido" style="width:78%; font-size:95%" value="<?= $idPedido ?>">Email Empresas com Minuta de Contrato
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                    </div>
                 </div>
                
            <?php
            }
        }
        ?>

    </section>
</div>