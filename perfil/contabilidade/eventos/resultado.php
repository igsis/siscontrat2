<?php
$con = bancoMysqli();

$protocolo = '';
$numProcesso = '';
$usuario = '';
$status = '';
$where = '';

if (isset($_POST['protocolo']) && $_POST['protocolo'] != null) {
    $protocolo = $_POST['protocolo'];
    $protocolo = " AND p.protocolo = '$protocolo' ";
}

if (isset($_POST['numProcesso']) && $_POST['numProcesso'] != null) {
    $numProcesso = $_POST['num_processo'];
    $numProcesso = " AND fc.num_processo_pagto = '$numProcesso' ";
}

if (isset($_POST['usuario']) && $_POST['usuario'] != null) {
    $usuario = $_POST['usuario'];
    $usuario = " AND usuario_id = '$usuario' ";
}

if (isset($_POST['tipo_evento']) && $_POST['tipo_evento'] != null) {
    $tipo_evento = $_POST['tipo_evento'];
    $tipo_evento = " AND tipo_evento_id = '$tipo_evento' ";
}

if (isset($_POST['instituicao']) && $_POST['instituicao'] != null) {
    $instituicao = $_POST['instituicao'];
    $instituicao = " AND instituicao_id = '$instituicao' ";
}

if (isset($_POST['rel_jur']) && $_POST['rel_jur'] != null) {
    $rel_jur = $_POST['rel_jur'];
    $rel_jur = " AND relacao_juridica_id = '$rel_jur' ";
}

if (isset($_POST['status']) && $_POST['status'] != null) {
    $status = $_POST['status'];
    $status = " AND p.status_pedido_id = '$status' ";
}

$sql = "SELECT e.nome_evento,
               e.tipo_evento_id,
               p.id,
               p.numero_processo,
               p.pessoa_tipo_id,
               p.pessoa_fisica_id,
               p.pessoa_juridica_id,
               e.protocolo, 
               st.status
               FROM pedidos AS p 
               INNER JOIN eventos AS e ON e.id = p.origem_id
               INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
               INNER JOIN pedido_status AS st ON p.status_pedido_id = st.id
               WHERE p.origem_tipo_id = 2 AND p.publicado = 1 AND e.publicado = 1 $status $numProcesso $protocolo";
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Contabilidade</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Resultado da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblEventos" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Processo</th>
                        <th>Protocolo</th>
                        <th>Proponente</th>
                        <th>Tipo</th>
                        <th>Objeto</th>
                        <th>Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    if ($query = mysqli_query($con, $sql)) {
                        while ($pedido = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td>
                                    <form action="?perfil=contabilidade&p=eventos&sp=detalhes" role="form"
                                          method="POST">
                                        <input type="hidden" name="idPedido" id="idPedido" value="<?= $pedido['id'] ?>">
                                        <button type="submit"
                                                class="btn btn-primary"><?= $pedido['numero_processo'] ?></button>
                                    </form>
                                </td>
                                <td><?= $pedido['protocolo'] ?></td>
                                <?php
                                if ($pedido['pessoa_tipo_id'] == 1) {
                                    $tipo = "Física";
                                    $pessoa = recuperaDados("pessoa_fisicas", 'id', $pedido['pessoa_fisica_id'])['nome'];
                                } else if ($pedido['pessoa_tipo_id'] == 2) {
                                    $tipo = "Jurídica";
                                    $pessoa = recuperaDados("pessoa_juridicas", 'id', $pedido['pessoa_juridica_id'])['razao_social'];
                                }
                                ?>
                                <td><?= $pessoa ?></td>
                                <td><?= $tipo ?></td>
                                <?php
                                $objeto = retornaTipo($pedido['tipo_evento_id']) . " - " . $pedido['nome_evento'];
                                ?>
                                <td><?= $objeto ?></td>
                                <td><?= $pedido['status'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th>Processo</th>
                        <th>Protocolo</th>
                        <th>Proponente</th>
                        <th>Tipo</th>
                        <th>Objeto</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=contabilidade&p=eventos&sp=pesquisa">
                    <button type="button" class="btn btn-default">Voltar a pesquisa</button>
                </a>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEventos').DataTable({
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
