<?php

$idFormacao = $_SESSION['formacaoId'];
$con = bancoMysqli();

if (isset($_POST['mdlPadrao'])) {
    $modelo = $_POST['idPadrao'];
}
if (isset($_POST['mdlVoca'])) {
    $modelo = $_POST['idVoca'];
}
if (isset($_POST['mdlPia'])) {
    $modelo = $_POST['idPia'];
}
if (isset($_POST['mdlOficina'])) {
    $modelo = $_POST['idOficina'];
}

$sql = "SELECT 
    p.numero_processo,
    fc.protocolo,
    pf.nome,
    p.valor_total,
    p.forma_pagamento
    
    FROM pedidos as p
    INNER JOIN formacao_contratacoes fc on p.origem_id = fc.id
    INNER JOIN pessoa_fisicas pf on fc.pessoa_fisica_id = pf.id
    
    WHERE fc.publicado = 1 AND p.origem_tipo_id AND p.origem_id = $idFormacao";
$formacao = $con->query($sql)->fetch_array();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhes do evento selecionado</h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $formacao['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do Processo:</th>
                        <td><?= $formacao['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Contratado:</th>
                        <td><?= $formacao['nome'] ?></td>
                    </tr>
                    <?php
                    $sqlLocal = "SELECT 
                    l.local
                    FROM formacao_locais as fl
                    INNER JOIN locais l on fl.local_id = l.id
                    WHERE l.publicado = 1 ";
                    $local = $con->query($sqlLocal)->fetch_assoc();
                    ?>

                    <tr>
                        <th width="30%">Local:</th>
                        <td><?= $local['local'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Valor:</th>
                        <td><?= $formacao['valor_total'] ?></td>
                    </tr>
                    <?php
                    $sqlPeriodo = "SELECT data_inicio, data_fim
                    FROM ocorrencias 
                    WHERE publicado = 1";
                    $periodo = $con->query($sqlPeriodo)->fetch_array();
                    ?>
                    <tr>
                        <th width="30%">Período:</th>
                        <td>De <?=$periodo['data_inicio']?> a <?=$periodo['data_fim']?></td>
                    </tr>
                    <tr>
                        <th width="30%">Forma de pagamento:</th>
                        <td><?= $formacao['forma_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $sqlModelo = "SELECT * FROM modelo_juridicos where id = $modelo";
                        $mdl = $con->query($sqlModelo)->fetch_assoc();
                        ?>

                        <th width="30%">Amparo:</th>
                        <td><?= $mdl['amparo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Finalização:</th>
                        <td><?= $mdl['finalizacao'] ?></td>
                    </tr>
                </table>
                <div class="pull-left">
                    <a href="?perfil=juridico&p=filtrar_formacao&sp=pesquisa_formacao">
                        <button type="button" class="btn btn-default">Voltar a pesquisa</button>
                    </a>
                </div>
                <div class="pull-right">
                    <a href="?perfil=juridico&p=tipo_modelo&sp=detalhes_evento">
                        <button type="button" class="btn btn-default">Detalhes do evento</button>
                    </a>
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