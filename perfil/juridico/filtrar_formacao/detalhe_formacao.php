<?php

$con = bancoMysqli();

isset($_POST['idFormacao']);
$idFormacao = $_POST['idFormacao'];
isset($_POST['tipoModelo']);
$modelo = $_POST['tipoModelo'];

if(isset($_POST['detalhe'])) {
    $amparo = $_POST['amparo'];
    $finalizacao = $_POST['finalizacao'];
    $dotacao = $_POST['dotacao'];
}


$formacao = recuperaDados('formacao_contratacoes', 'id', $idFormacao);
$pedido = recuperaDados('pedidos', 'id', $formacao['pedido_id']);
$idPedido = $pedido['id'];
$pf = recuperaDados('pessoa_fisicas', 'id', $formacao['pessoa_fisica_id']);
$ci = recuperaDados('classificacao_indicativas', 'id', $formacao['classificacao']);
$linguagem = recuperaDados('linguagens ', 'id', $formacao['linguagem_id']);
$programa = recuperaDados('programas', 'id', $formacao['programa_id']);
$pagamento = recuperaDados('pagamentos', 'pedido_id', $pedido['id']);
$status = recuperaDados('pedido_status', 'id', $pedido['status_pedido_id']);

/// inserindo dados dentro de juridicos ///

    $sql = "SELECT * FROM juridicos where pedido_id = '$idPedido'";
    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);
    if ($num > 0) {
        $sqlUptate = "UPDATE juridicos SET pedido_id = $idPedido, amparo_legal = '$amparo', finalizacao = '$finalizacao', dotacao ='$dotacao'
    WHERE pedido_id = $idPedido";
        $sqlUptate = mysqli_query($con, $sqlUptate);
    } else {
        $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao)
        VALUES ('$idPedido','$amparo','$finalizacao','$dotacao')";
        $sqlInsert = mysqli_query($con, $sqlInsert);
    }


//  local //
$sqlLocal = "SELECT l.local 
FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

// pegando a vigencia
$idVigencia = $formacao['form_vigencia_id'];

// usuarios //
$usuarios = recuperaDados('usuarios', 'id', $formacao['usuario_id']);

// fiscal , suplente //
$suplente = recuperaDados('usuarios', 'id', $formacao['suplente_id']);
$fiscal = recuperaDados('usuarios', 'id', $formacao['fiscal_id']);


// pegando telefone de pf_telefones //
$idPf = $formacao ['pessoa_fisica_id'];
$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}
$tel = substr($tel, 0, -3);

?>


<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">ID do evento:</th>
                        <td><?= $idFormacao ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Enviado em:</th>
                        <td><?= exibirDataHoraBr($formacao['data_envio']) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou a formação:</th>
                        <td><?= $usuarios ['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $usuarios['telefone'] ?> </td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $usuarios ['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td><?= $usuarios['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $usuarios['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $usuarios['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Suplente:</th>
                        <td><?= $suplente['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $suplente['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $suplente['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Ficha técnica:</th>
                        <td><?= $pf ['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $ci['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $linguagem['linguagem'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                </table>
                <h1>Especificidades</h1>
                <br>
                <tr>
                    <td></td>
                </tr>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoFormacao($idVigencia) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td>
                            <?php
                            while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                $local = $local . $linhaLocal['local'] . ' - ';
                            }
                            $local = substr($local, 0, -3);
                            echo $local;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td><?= $pf['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $pf['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $tel ?></td>
                    </tr>
                </table>
                <h3>Pedidos de contratação</h3>
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $formacao['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do processo:</th>
                        <td><?= $pedido['numero_processo'] ?></td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td><?= $programa['programa'] ?>-<?= $linguagem['linguagem'] ?>-<?= $programa['edital'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td>
                            <?php
                            while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                $local = $local . $linhaLocal['local'] . '-';
                            }

                            $local = substr($local, 0, -3);
                            echo $local;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Valor</th>
                        <td><?= $pedido['valor_total'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Forma de Pagamento</th>
                        <td><?=$pedido['forma_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data/Período</th>
                        <td><?= retornaPeriodoFormacao($idVigencia) ?></td>
                    </tr>
                    <?php

                    if($pagamento == null || $pagamento == ""){ ?>

                    <tr>
                        <th width="30%">Data de Emissão da N.E:</th>
                        <td>Não cadastrado</td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Entrega da N.E</th>
                        <td>Não cadastrado</td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <th width="30%">Data de Emissão da N.E:</th>
                        <td><?= exibirDataBr($pagamento['emissao_nota_empenho']) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Entrega da N.E</th>
                        <td><?= exibirDataBr($pagamento['entrega_nota_empenho']) ?></td>
                    </tr>
                    <?php }?>
                    <tr>
                        <th width="30%">Dotação Orçamentária:</th>
                        <td><?= $dotacao ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observação:</th>
                        <td><?= $pedido['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Último status:</th>
                        <td><?= $status['status'] ?></td>
                    </tr>
                </table>
                <br/>
                <div class="pull-left">
                    <form action="?perfil=juridico&p=filtrar_formacao&sp=resumo_formacao" method="post">
                        <input type="hidden" name="idFormacao" value="<?= $idFormacao ?>">
                        <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                        <button type="submit" class="btn btn-default pull-right">Voltar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblFormacao').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>
