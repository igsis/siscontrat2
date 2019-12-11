<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";


if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
}


$sqlModelo = "SELECT * FROM modelo_juridicos where id = $modelo";
$mdl = $con->query($sqlModelo)->fetch_assoc();

$sql = "SELECT p.numero_processo,
            p.forma_pagamento,
            p.valor_total,
            fc.protocolo, 
            pf.nome, 
            fs.status,
            fc.id
            

        FROM pedidos as p INNER JOIN formacao_status fs on p.id = fs.id 
        INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id 
        INNER JOIN formacao_contratacoes fc on p.origem_id = fc.id 
        WHERE p.publicado = 1 AND p.origem_tipo_id = 2 AND fc.publicado = 1";
$query = $con->query($sql)->fetch_assoc();

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
                            <td><?=$query['protocolo']?></td>
                        </tr>
                        <tr>
                            <th width="30%">Número do Processo:</th>
                            <td><?=$query['numero_processo']?></td>
                        </tr>
                        <tr>
                            <th width="30%">Contratado:</th>
                            <td><?=$query['nome']?></td>
                        </tr>
                        <tr>
                            <th width="30%">Local:</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?=$query['valor_total']?></td>
                        </tr>
                        <tr>
                            <th width="30%">Período:</th>
                            <td><?= retornaPeriodoNovo($idPedido,'ocorrencias') ?></td>

                        </tr>
                        <tr>
                            <th width="30%">Forma de pagamento:</th>
                            <td><?=$query['forma_pagamento']?></td>
                        </tr>
                        <tr>
                            <th width="30%">Amparo:</th>
                            <td><textarea name="amparo" rows="6" cols="85"><?= $mdl['amparo'] ?></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária</th>
                            <td><textarea name="dotacao" rows="1" cols="85"></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Finalização:</th>
                            <td><textarea name="finalizar" rows="8" cols="85"><?= $mdl['finalizacao'] ?></textarea></td>
                        </tr>
                    </table>
                    <div class="pull-left">
                        <?php // ADICIONAR ANCORA PARA VOLTAR ?>
                    </div>
                    <input type="hidden" name="idEvento" value="">
                    <button type="submit" name="enviar" value="GRAVAR" class="btn btn-info pull-left">Gravar
                    </button>
        </form>
        <form action="?perfil=juridico&p=tipo_modelo&sp=detalhes_evento" method="post">
            <input type="hidden" name="idEvento" value="">
            <input type="hidden" name="idModelo" value="">
            <button type="submit" name="detalheEvento" class="btn btn-info pull-right">Detalhes evento
            </button>
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