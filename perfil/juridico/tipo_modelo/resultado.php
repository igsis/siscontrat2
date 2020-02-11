<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";


$idEvento = $_SESSION['eventoId'];

if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
}


$sqlModelo = "SELECT * FROM modelo_juridicos where id = $modelo";
$mdl = $con->query($sqlModelo)->fetch_assoc();
$eve = recuperaDados('eventos', 'id', $idEvento);

$fiscal = recuperaDados('usuarios', 'id', $eve['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $eve['suplente_id'])['nome_completo'];
$rfFiscal = recuperaDados('usuarios','id',$eve['fiscal_id'])['rf_rg'];
$rfSuplente = recuperaDados('usuarios','id',$eve['suplente_id'])['rf_rg'];
$mdl = str_replace("nomeFiscal", $fiscal, $mdl);
$mdl = str_replace("rfFiscal", $rfFiscal, $mdl);
$mdl = str_replace("nomeSuplente", $suplente, $mdl);
$mdl = str_replace("rfSuplente", $rfSuplente, $mdl);




$sql = "SELECT 
    p.numero_processo,
    p.forma_pagamento,
    p.valor_total,
    pf.nome,
    p.origem_tipo_id,
    p.origem_id,
    e.protocolo
    
    
    
    FROM pedidos as p
    INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
    INNER JOIN eventos e on e.id = p.origem_id
    
    WHERE p.publicado = 1 AND p.origem_tipo_id AND p.origem_id = $idEvento";
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
                            <th width="30%">Contratado:</th>
                            <td><?= $evento['nome'] ?></td>
                        </tr>
                        <?php
                        $sqlLocal = "SELECT 
                    l.local
                    from locais l 
                    INNER JOIN ocorrencias o on l.id = o.local_id";
                        $local = $con->query($sqlLocal)->fetch_assoc();
                        ?>
                        <tr>
                            <th width="30%">Local:</th>
                            <td><?= $local['local'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?= $evento['valor_total'] ?></td>
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
                            <td><textarea name="amparo" rows="6" cols="85"><?= $mdl['amparo'] ?></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária</th>
                            <td><textarea name="dotacao" rows="1" cols="85"></textarea></td>                        </tr>
                        <tr>
                            <th width="30%">Finalização:</th>
                            <td><textarea name="finalizacao" rows="8" cols="85"><?= $mdl['finalizacao'] ?></textarea></td>
                        </tr>
                    </table>
                    <div class="pull-left">
                        <?php // ADICIONAR ANCORA PARA VOLTAR ?>
                    </div>
                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                    <button type="submit" name="enviar" value="GRAVAR" class="btn btn-info pull-left">Gravar
                    </button>
        </form>
        <form action="?perfil=juridico&p=tipo_modelo&sp=detalhes_evento" role="form" method="post">
            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
            <button type="submit" class="btn btn-info pull-right">Detalhes evento</button>
        </form>
</div>
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