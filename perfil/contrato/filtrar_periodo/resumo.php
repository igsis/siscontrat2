<?php
$con = bancoMysqli();

if (isset($_POST['selecionar'])) {
    $idPedido = $_POST['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];
    $idPf = $_POST['idPf'];
    if ($_POST['liderOn'] != 1) {
        $sql = "UPDATE pedidos SET pessoa_fisica_id = '$idPf', pessoa_tipo_id = 1, pessoa_juridica_id = null WHERE id = '$idPedido'";

        if (mysqli_query($con, $sql))
            $mensagem = mensagem("success", "Troca efetuada com sucesso!");
        else
            $mensagem = mensagem("danger", "Ocorreu um erro ao trocar proponente! Tente novamente.");
    }
} else if (isset($_POST['selecionarPj'])) {
    $idPj = $_POST['idPj'];
    $idPedido = $_POST['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];


    $sql = "UPDATE pedidos SET pessoa_juridica_id = '$idPj', pessoa_tipo_id = 2, pessoa_fisica_id = null WHERE id ='$idPedido'";

    if (mysqli_query($con, $sql))
        $mensagem = mensagem("success", "Troca efetuada com sucesso!");
    else
        $mensagem = mensagem("danger", "Ocorreu um erro ao trocar proponente! Tente novamente.");

} else if (isset($_POST['cadastraLider'])) {
    $idPf = $_POST['idPf'];
    $idAtracao = $_SESSION['idAtracao'];
    $idPedido = $_SESSION['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];
    unset($_SESSION['idAtracao']);
    unset($_SESSION['idPedido']);

    $sql = "SELECT * FROM lideres WHERE atracao_id = '$idAtracao' AND pedido_id = '$idPedido'";
    $query = mysqli_query($con, $sql);

    $num = mysqli_num_rows($query);

    if ($num > 0)
        $sql = "UPDATE lideres SET pessoa_fisica_id = '$idPf' WHERE atracao_id = '$idAtracao' AND pedido_id = '$idPedido'"; #update
    else
        $sql = "INSERT INTO lideres (pedido_id, atracao_id, pessoa_fisica_id) VALUE ('$idPedido', '$idAtracao', '$idPf')"; #insert

    if (mysqli_query($con, $sql)) {
        #foi
        $mensagem = mensagem("success", "Troca realizada com sucesso!");

        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao efetuar a troca!");
    }

} else {
    $idEvento = $_POST['idEvento'];
}

$_SESSION['idEvento'] = $idEvento;

$evento = recuperaDados('eventos', 'id', $idEvento);
$sql = "SELECT * FROM pedidos where origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$query = mysqli_query($con, $sql);
$pedido = mysqli_fetch_array($query);

$idPedido = $pedido['id'];
if (isset($_POST['salvar'])) {
    if ($nivelUsuario == 1) { // alterar o operador e/ou o status do pedido
        $operador = $_POST['operador'];
        $status = $_POST['status'];

        $sql = "UPDATE contratos SET usuario_contrato_id = '$operador' WHERE pedido_id = '$idPedido'";
        if (mysqli_query($con, $sql))
            gravarLog($sql);

        $sql = "UPDATE pedidos SET status_pedido_id = '$status' WHERE id = '$idPedido'";
        if (mysqli_query($con, $sql))
            gravarLog($sql);
    }

    $idAtracao = $_POST['idAtracao'];
    $nome_atracao = $_POST['nome_atracao'];
    $integrantes = $_POST['integrantes'];

    for ($i = 0; $i < count($idAtracao); $i++) { // altera de uma ou de todas as atracoes (nome da atracao e integrantes)
        $baldeId = $idAtracao[$i];
        $baldeNome = $nome_atracao[$i];
        $baldeIntegrantes = $integrantes[$i];

        $sql = "UPDATE atracoes SET 
                    nome_atracao = '$baldeNome', 
                    integrantes = '$baldeIntegrantes' 
                    WHERE id = '$baldeId'";

        mysqli_query($con, $sql);
    }

    //pedidos
    $formaPagamento = $_POST['formaPagamento'];
    $verba = $_POST['verba'];
    $processoMae = $_POST['processoMae'];
    $processo = $_POST['processo'];
    $justificativa = $_POST['justificativa'];
    $operador = $_POST['operador'] ?? NULL;

    //eventos
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'] ?? null;

    $sqlEvento = "UPDATE eventos SET fiscal_id = '$fiscal', suplente_id ='$suplente' WHERE id = '$idEvento'";
    $sqlPedido = "UPDATE pedidos SET numero_processo = '$processo', numero_processo_mae = '$processoMae', forma_pagamento = '$formaPagamento', justificativa = '$justificativa', verba_id = '$verba' WHERE id = '$idPedido'";


    if (mysqli_query($con, $sqlPedido) && mysqli_query($con, $sqlEvento)) {
        if($operador != NULL){
            $trocaOp = $con->query("UPDATE pedidos SET operador_id = '$operador' WHERE id = $idPedido");
        }
        gravarLog($sqlEvento);
        gravarLog($sqlPedido);
        $mensagem = mensagem("success", "Atualizações salvas com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao salvar alterações! Tente novamente.");
    }
}


$evento = recuperaDados('eventos', 'id', $idEvento);
$sql = "SELECT * FROM pedidos where origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$query = mysqli_query($con, $sql);
$pedido = mysqli_fetch_array($query);

if ($pedido['pessoa_tipo_id'] == 1) {
    $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
    $idPf = $pedido['pessoa_fisica_id'];
} else {
    $proponente = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
    $idPj = $pedido['pessoa_juridica_id'];
}

$_SESSION['idPedido'] = $idPedido;

$contrato = recuperaDados('contratos', 'pedido_id', $pedido['id']);
$sqlAtracao = "SELECT * FROM atracoes where evento_id = '$idEvento' AND publicado = 1";
$queryAtracao = mysqli_query($con, $sqlAtracao);

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Contrato</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Contrato do evento: <?= $evento['nome_evento'] ?></h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=filtrar_periodo&sp=resumo"
                          role="form">
                        <div class="box-body">

                            <?php
                            if ($nivelUsuario == 1 || $nivelUsuario == 2) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 from-group">
                                        <label for="operador">Operador</label>
                                        <select name="operador" id="operador" class="form-control">
                                            <option value="">Selecione um operador</option>
                                            <?php
                                            geraOpcao('usuarios u INNER JOIN usuario_contratos uc on uc.usuario_id = u.id', $pedido['operador_id']);
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="status">Status Contrato</label>
                                        <select name="status" id="status" class="form-control">
                                            <?php
                                            geraOpcao('pedido_status', $pedido['status_pedido_id']);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <?php
                            }
                            ?>

                            <?php
                            while ($atracao = mysqli_fetch_array($queryAtracao)) {
                                $_SESSION['idAtracao'] = $atracao['id'];
                                ?>
                                <div class="row">
                                    <input type="hidden" name="idAtracao[]" value="<?= $atracao['id'] ?>">

                                    <div class="form-group col-md-6">
                                        <label for="nome_atracao[]">Nome da atração *</label>
                                        <input type="text" name="nome_atracao[]" id="nome_atracao"
                                               value="<?= $atracao['nome_atracao'] ?>"
                                               class="form-control" required>

                                        <br>

                                        <label for="valor">Valor: </label>
                                        <input type="text" disabled
                                               value="<?= dinheiroParaBr($atracao['valor_individual']) ?>"
                                               class="form-control">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="integrantes[]">Integrantes* </label>
                                        <textarea name="integrantes[]" id="integrantes" required rows="5"
                                                  class="form-control"><?= $atracao['integrantes'] ?></textarea>
                                    </div>
                                </div>
                                <hr>
                                <?php
                            }
                            ?>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="formaPagamento">Forma de pagamento</label>
                                    <textarea name="formaPagamento" id="formaPagamento" rows="5" required
                                              class="form-control"><?= $pedido['forma_pagamento'] ?> </textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa</label>
                                    <textarea name="justificativa" id="justificativa" rows="5" required
                                              class="form-control"><?= $pedido['justificativa'] ?> </textarea>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="verba">Verba </label>
                                    <select name="verba" id="verba" class="form-control">
                                        <?php
                                        geraOpcao('verbas', $pedido['verba_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="processoMae">Número de processo mãe</label>
                                    <input type="text" class="form-control" name="processoMae" id="processoMae"
                                           data-mask="9999.9999/9999999-9" minlength="19"
                                           value="<?= $pedido['numero_processo_mae'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="processo">Número de processo</label>
                                    <input type="text" class="form-control" name="processo" id="processo"
                                           data-mask="9999.9999/9999999-9" minlength="19"
                                           value="<?= $pedido['numero_processo'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fiscal">Fiscal *</label>
                                    <select class="form-control" id="fiscal" name="fiscal" required>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['fiscal_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="suplente">Suplente</label>
                                    <select class="form-control" id="suplente" name="suplente">
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['suplente_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="hidden" name="idEvento" class="idEvento" value="<?= $idEvento ?>">
                            <button type="submit" name="salvar" id="salvar" class="btn btn-primary pull-right">
                                Salvar
                            </button>
                    </form>
                    <form action="?perfil=contrato&p=filtrar_periodo&sp=area_impressao" method="post" role="form">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-default pull-left">Ir para a área de impressão</button>
                    </form>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                        <form action="?perfil=contrato&p=filtrar_periodo&sp=pesquisa_contratos"
                              method="post">
                            <button type="submit" class="btn btn-info" name="reabertura" style="width: 35%;"
                                    id="reabertura">
                                Reabertura
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>

<?php
if ($pedido['pessoa_tipo_id'] == 1) {
    ?>
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Proponente</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Proponente</th>
                    <th width="5%">Editar</th>
                    <th width="5%">Trocar</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $proponente['nome'] ?></td>
                    <td>
                        <form action="?perfil=contrato&p=filtrar_periodo&sp=edita_pf" method="POST">
                            <input type="hidden" name="idPf" id="idPf" value="<?= $idPf ?>">
                            <button type="submit" class="btn btn-primary btn-block"><span
                                        class="glyphicon glyphicon-pencil"></span></button>
                        </form>
                    </td>
                    <td>
                        <form action="?perfil=contrato&p=filtrar_periodo&sp=tipo_pessoa"
                              method="POST">
                            <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-info btn-block"><span
                                        class="glyphicon glyphicon-random"></span></button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} else if ($pedido['pessoa_tipo_id'] == 2) {
    $sql_atracao = "SELECT a.id, a.nome_atracao, pf.nome, l.pessoa_fisica_id FROM atracoes AS a                                              
                                            LEFT JOIN lideres l on a.id = l.atracao_id
                                            left join pessoa_fisicas pf on l.pessoa_fisica_id = pf.id
                                            WHERE evento_id = '$idEvento' AND a.publicado = 1";
    $query_atracao = mysqli_query($con, $sql_atracao);
    ?>
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Proponente</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Proponente</th>
                    <th width="5%">Editar</th>
                    <th width="5%">Trocar</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $proponente['razao_social'] ?></td>
                    <td>
                        <form action="?perfil=contrato&p=filtrar_periodo&sp=edita_pj" method="POST">
                            <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="idPj" id="idPj" value="<?= $idPj ?>">
                            <button type="submit" name="load" id="load" class="btn btn-primary btn-block"><span
                                        class="glyphicon glyphicon-pencil"></span></button>
                        </form>
                    </td>
                    <td>
                        <form action="?perfil=contrato&p=filtrar_periodo&sp=tipo_pessoa"
                              method="POST">
                            <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-info btn-block"><span
                                        class="glyphicon glyphicon-random"></span></button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Líderes</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Atração</th>
                    <th>Proponente</th>
                    <th width="5%">Ação</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($atracao = mysqli_fetch_array($query_atracao)) {
                    ?>
                    <tr>
                        <td><?= $atracao['nome_atracao'] ?></td>
                        <?php
                        if ($atracao['pessoa_fisica_id'] > 0) {
                            ?>
                            <td><?= $atracao['nome'] ?></td>
                            <td>
                                <form method="POST"
                                      action="?perfil=contrato&p=filtrar_periodo&sp=pesquisa_lider"
                                      role="form">
                                    <input type='hidden' name='oficina' value="<?= $atracao['id'] ?>">
                                    <input type='hidden' name='lider' value='<?= $idPedido ?>'>
                                    <button type="submit" name='carregar' class="btn btn-primary"><i
                                                class='fa fa-refresh'></i></button>
                                </form>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td></td>
                            <td>
                                <form method="POST"
                                      action="?perfil=contrato&p=filtrar_periodo&sp=pesquisa_lider"
                                      role="form">
                                    <input type='hidden' name='oficina' value="<?= $atracao['id'] ?>">
                                    <input type='hidden' name='lider' value='<?= $idPedido ?>'>
                                    <button type="submit" name='pesquisar' class="btn btn-primary
                                                "><i class='fa fa-plus'></i></button>
                                </form>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>
</section>
</div>

