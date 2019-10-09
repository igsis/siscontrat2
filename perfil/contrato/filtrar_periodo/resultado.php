<?php
$con = bancoMysqli();

if (isset($_POST['busca'])) {
    $data_inicio = $_POST['data_inicio'] ?? NULL;
    $data_fim = $_POST['data_fim'] ?? NULL;
    $operador = $_POST['operador'] ?? NULL;

    $sqlOperador = '';

    if ($operador != null && $operador != 0)
        $sqlOperador = " AND usuario_id = '$operador'";

    $sql = "SELECT e.protocolo, p.numero_processo, p.pessoa_tipo_id, 
    p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, 
    p.valor_total, e.evento_status_id, e.usuario_id, es.status
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN evento_status es on e.evento_status_id = es.id
    INNER JOIN ocorrencias o on e.id = o.origem_ocorrencia_id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND o.data_inicio >= '$data_inicio'
    AND o.data_fim <= '$data_fim'
    $sqlOperador
    group by e.id";

    $query = mysqli_query($con, $sql);
    $num_rows = mysqli_num_rows($query);
}

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Número de processo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Operador</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_rows == 0) {
                                ?>
                                <tr>
                                    <th colspan="7"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    if ($evento['pessoa_tipo_id'] == 1)
                                        $pessoa = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id'])['nome_artistico'];
                                    else if ($evento['pessoa_fisica_id'] == 2)
                                        $pessoa = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id'])['razao_social'];

                                    ?>
                                    <td><a href="#"><?= $evento['protocolo'] ?></a></td>
                                    <td><?= $evento['numero_processo'] ?></td>
                                    <td><?= $pessoa ?></td>
                                    <td><?= $evento['nome_evento'] ?></td>
                                    <td>R$ <?= dinheiroParaBr($evento['valor_total']) ?></td>
                                    <td><?= $evento['status'] ?></td>
                                    <td><?= recuperaDados('usuarios', 'id', $evento['usuario_id'])['nome_completo'] ?></td>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Número de processo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Operador</th>
                            </tr>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=contrato&p=filtrar_periodo&sp=pesquisa_contratos">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblResultado').DataTable({
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