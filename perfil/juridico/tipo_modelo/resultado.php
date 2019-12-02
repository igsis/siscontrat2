<?php
$con = bancoMysqli();
$idEvento = $_SESSION['eventoId'];

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
                        <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?></td>

                    </tr>
                    <tr>
                        <th width="30%">Forma de pagamento:</th>
                        <td><?= $evento['forma_pagamento'] ?></td>
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
                    <?php // ADICIONAR ANCORA PARA VOLTAR ?>
                </div>
                <div class="pull-right">
                    <form action="?perfil=juridico&p=tipo_modelo&sp=detalhes_evento" method="post">
                        <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                        <input type="hidden" name="idModelo" value="<?= $modelo ?>">
                        <button type="submit" name="detalheEvento" class="btn btn-info pull-right">Detalhes evento
                        </button>
                    </form>
                </div>
                <form action="#" method="POST">
                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                    <input type="hidden" name="idModelo" value="<?= $modelo ?>">
                    <button type="submit" name="geraPadrao" class="btn btn-info pull-left">Gravar</button>
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