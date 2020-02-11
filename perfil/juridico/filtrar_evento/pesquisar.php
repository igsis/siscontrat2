<?php
$con = bancoMysqli();
$protocolo = '';
$numprocesso = '';
$objetoevento = '';
$usuariocadastro = '';
$tipoEvento = '';
$instituicao = '';
$statusPedido = '';


if (isset($_POST['protocolo']) && $_POST['protocolo'] != null) {
    $protocolo = $_POST ['protocolo'];
    $protocolo = " AND e.protocolo LIKE '%$protocolo%'";
}
if (isset($_POST['numprocesso']) && $_POST['numprocesso'] != null) {
    $numprocesso = $_POST ['numprocesso'];
    $numprocesso = " AND p.numero_processo LIKE '%$numprocesso%'";
}
if (isset($_POST['objetoevento']) && $_POST['objetoevento'] != null) {
    $objetoevento = $_POST ['objetoevento'];
}
if (isset($_POST['usuariocadastro']) && $_POST['usuariocadastro'] != null) {
    $usuariocadastro = $_POST ['usuariocadastro'];
    $usuariocadastro = " AND e.usuario_id = '$usuariocadastro'";
}
if (isset($_POST['tipoEvento']) && $_POST['tipoEvento'] != null) {
    $tipoEvento = $_POST ['tipoEvento'];
    $tipoEvento = "AND e.tipo_evento_id = '$tipoEvento'";
}
if (isset($_POST['instituicao']) && $_POST['instituicao'] != null) {
    $instituicao = $_POST ['instituicao'];
    $instituicao = "AND o.instituicao_id = '$instituicao'";
}
if (isset($_POST['statusPedido']) && $_POST['statusPedido']) {
    $statusPedido = $_POST['statusPedido'];
    $statusPedido = " AND p.status_pedido_id = '$statusPedido'";
}

$sql = "SELECT p.numero_processo,
               e.protocolo,
               pf.nome,
               te.tipo_evento,
               p.pessoa_tipo_id,
               p.pessoa_fisica_id,
               p.pessoa_juridica_id,
               e.tipo_evento_id,
               e.nome_evento,
               e.id
               

            FROM pedidos as p
            INNER JOIN eventos AS e ON e.id = p.origem_id
            INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
            INNER JOIN tipo_eventos te on e.tipo_evento_id = te.id
            
            where p.origem_tipo_id = 1 
            and p.publicado = 1 
            and e.publicado = 1 $protocolo $numprocesso $objetoevento
            $tipoEvento $instituicao $statusPedido";

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Buscar por evento</h3>
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
                        <th>Pendências</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($query = mysqli_query($con, $sql)) {
                        while ($evento = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <?php
                                if (isset($evento['numero_processo'])) {
                                    ?>
                                    <td>
                                        <form action="?perfil=juridico&p=tipo_modelo&sp=seleciona_modelo" role="form"
                                              method="POST">
                                            <input type="hidden" name=idEvento value="<?= $evento['id'] ?>">
                                            <button type="submit"
                                                    class="btn btn-link"><?= $evento['numero_processo'] ?></button>
                                        </form>
                                    </td>
                                    <?php
                                } else {
                                    echo "<td>Não possui</td>";
                                }
                                ?>
                                <td><?= $evento['protocolo'] ?></td>
                                <?php
                                if ($evento['pessoa_tipo_id'] == 1) {
                                    $tipo = "Física";
                                    $pessoa = recuperaDados("pessoa_fisicas", "id", $evento ['pessoa_fisica_id'])['nome'];
                                } else if ($evento['pessoa_tipo_id'] == 2) {
                                    $tipo = "Jurídica";
                                    $pessoa = recuperaDados("pessoa_juridicas", "id", $evento['pessoa_juridica_id']['razao_social']);
                                }
                                ?>
                                <td><?= $pessoa ?></td>
                                <td><?= $tipo ?></td>
                                <?php
                                $objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
                                ?>
                                <td><?= $objeto ?></td>
                                <td>
                                    <?php
                                    $erros = [];
                                    if (count($erros) == 0) { ?>
                                        <p>Seu evento não possui pendências!</p>
                                    <?php } else { ?>
                                        <p>Seu evento possui pendências!</p>
                                        <ul>
                                            <?php foreach ($erros as $erro) {
                                                echo "<li>$erro</li>";
                                            }
                                            ?>
                                        </ul>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>

                </table>
            </div>
            <div class="box-footer">
                <a href="#">
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