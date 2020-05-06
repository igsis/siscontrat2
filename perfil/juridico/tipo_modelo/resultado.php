<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
}
isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];


$mdl = recuperaDados('modelo_juridicos', 'id', $modelo);
$eve = recuperaDados('eventos', 'id', $idEvento);

$sqlLocal = "SELECT e.id,l.local FROM eventos as e
             INNER JOIN ocorrencias as o on o.origem_ocorrencia_id = e.id
             INNER JOIN locais as l on o.local_id = l.id
             WHERE e.publicado = 1 AND e.id = '$idEvento'";
$queryLocal = mysqli_query($con, $sqlLocal);
$local = '';
while ($locais = mysqli_fetch_array($queryLocal)) {
    $local = $local . " - " . $locais['local'];
}
$local = substr($local, 3);


$fiscal = recuperaDados('usuarios', 'id', $eve['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $eve['suplente_id'])['nome_completo'];
$rfFiscal = recuperaDados('usuarios', 'id', $eve['fiscal_id'])['rf_rg'];
$rfSuplente = recuperaDados('usuarios', 'id', $eve['suplente_id'])['rf_rg'];
$mdl = str_replace("nomeFiscal", $fiscal, $mdl);
$mdl = str_replace("rfFiscal", $rfFiscal, $mdl);
$mdl = str_replace("nomeSuplente", $suplente, $mdl);
$mdl = str_replace("rfSuplente", $rfSuplente, $mdl);


$sql = "select p.numero_processo, p.id as pedido_id,
e.protocolo,
p.valor_total,
p.forma_pagamento,
e.id,
p.pessoa_tipo_id,
p.pessoa_fisica_id,
p.pessoa_juridica_id
from pedidos as p
inner join eventos as e on e.id = p.origem_id
 AND e.publicado = 1
AND p.status_pedido_id != 1
AND p.status_pedido_id != 3
AND p.publicado = 1 where e.id = $idEvento";
$evento = $con->query($sql)->fetch_array();


?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <form action="?perfil=juridico&p=tipo_modelo&sp=dados_modelo" role="form" method="post">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalhes do evento selecionado</h3>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Protocolo:</th>
                            <td><?= $evento['protocolo'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Número do Processo:</th>
                            <td><?= $evento['numero_processo'] ?></td>
                        </tr>
                        <tr>
                            <?php
                            if ($evento['pessoa_tipo_id'] == 1) {
                                $tipo = "Física";
                                $pessoa = recuperaDados("pessoa_fisicas", "id", $evento ['pessoa_fisica_id']);
                                $nome = $pessoa['nome']
                                ?>
                                <th width="30%">Contratado:</th>
                                <td><?= $nome ?></td><?php
                            } else if ($evento['pessoa_tipo_id'] == 2) {
                                $tipo = "Jurídico";
                                $pessoa = recuperaDados('pessoa_juridicas', "id", $evento['pessoa_juridica_id']);
                                $nome = $pessoa['razao_social']; ?>
                                <th width="30%">Contratado:</th>
                                <td><?= $nome ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <th width="30%">Local:</th>
                            <td><?= $local ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?= "R$" . $evento['valor_total'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Período:</th>
                            <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias'); ?></td>

                        </tr>
                        <tr>
                            <th width="30%">Forma de pagamento:</th>
                            <td><?= $evento['forma_pagamento'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Amparo:</th>
                            <td><textarea class="form-control" name="amparo" rows="6"
                                          cols="85"><?= $mdl['amparo'] ?></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária</th>
                            <td><textarea class="form-control" name="dotacao" rows="1" cols="85"></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Finalização:</th>
                            <td><textarea class="form-control" name="finalizacao" rows="8"
                                          cols="85"><?= $mdl['finalizacao'] ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer">
                <form action="perfil=juridico&p=tipo_modelo&sp=dados_modelo" role="form" method="post">
                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                    <input type="hidden" name="idPedido" value="<?=$evento['pedido_id'] ?>">
                    <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                    <button type="submit" name="enviar" value="GRAVAR" class="btn btn-info pull-right"
                            style="margin: 0 5px;">Gravar
                    </button>
                </div>
        </form>
        <div class="box-footer">
            <form action="?perfil=juridico&p=tipo_modelo&sp=detalhes_evento" role="form" method="post">
                <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                <button type="submit" class="btn btn-info pull-right">Detalhes evento</button>
            </form>
        </div>
    </section>
</div>


<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEvento').DataTable({
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