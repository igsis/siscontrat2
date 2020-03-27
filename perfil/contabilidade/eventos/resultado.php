<?php
$con = bancoMysqli();

$sqlProtocolo = '';
$sqlProcesso = '';
$sqlNomeEvento = '';
$sqlProjeto = '';
$sqlUsuario = '';
$sqlStatus = '';
//$sqlRelacao = '';
//$sqlTipo = '';

if(isset($_POST['buscar'])){
    $protocolo = $_POST['protocolo'] ?? NULL;
    $numProcesso = $_POST['num_processo']  ?? NULL;
    $nomeEvento = $_POST['evento'] ?? NULL;
    $projeto = $_POST['projeto'] ?? NULL;
    $usuario = $_POST['usuario'] ?? NULL;
    $status = $_POST['status'] ?? NULL;
    /*$tipo_evento = $_POST['tipo_evento'] ?? NULL;
    $rel_jur = $_POST['rel_jur'] ?? NULL;*/
    
}

if ($protocolo != null) {
    $sqlProtocolo = " AND e.protocolo LIKE '%$protocolo%' ";
}

if ($numProcesso != null) {
    $sqlProcesso = " AND p.numero_processo LIKE '%$numProcesso%' ";
}

if ($nomeEvento != null){
    $sqlNomeEvento = " AND e.nome_evento LIKE '%$nomeEvento%'";
}

if ($projeto != null && $projeto != 0){
    $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";
}

if ($usuario != null && $usuario != 0){
    $sqlUsuario = " AND fiscal_id = '$usuario' OR suplente_id = '$usuario' OR usuario_id = '$usuario'";
}

if ($status != null) {
    $sqlStatus = " AND e.evento_status_id = '$status'";
}

/*if ($tipo_evento != null) {
    $sqlTipo = " AND e.tipo_evento_id = '$tipo_evento'";
}

if ($rel_jur != null) {
    $sqlRelacao = " AND e.relacao_juridica_id = '$rel_jur'";
}*/


$sql = "SELECT p.numero_processo, e.nome_evento, e.protocolo,
               p.pessoa_tipo_id, p.pessoa_fisica_id,
               p.pessoa_juridica_id, e.nome_evento, 
               e.tipo_evento_id, st.status, p.id
        FROM pedidos AS p 
        INNER JOIN eventos AS e ON e.id = p.origem_id 
        INNER JOIN evento_status AS st ON e.evento_status_id = st.id
        INNER JOIN usuarios AS u ON u.id = e.usuario_id
        INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id 
        WHERE p.origem_tipo_id = 1 AND p.publicado = 1 AND e.publicado = 1
        $sqlProtocolo $sqlProcesso $sqlNomeEvento $sqlUsuario $sqlProjeto $sqlStatus
        GROUP BY e.id";
               
               
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
                                <?php
                                if (isset($pedido['numero_processo'])) {
                                    ?>
                                    <td>
                                        <form action="?perfil=contabilidade&p=eventos&sp=detalhes" role="form"
                                              method="POST">
                                            <input type="hidden" name="idPedido" id="idPedido"
                                                   value="<?= $pedido['id'] ?>">
                                            <button type="submit"
                                                    class="btn btn-primary btn-block"><?= $pedido['numero_processo'] ?></button>
                                        </form>
                                    </td>
                                    <?php
                                }else {
                                    echo "<td>Não Possuí</td>";
                                }
                                ?>
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
