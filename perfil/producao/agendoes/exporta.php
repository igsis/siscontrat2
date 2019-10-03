<?php
$con = bancoMysqli();

if(isset($_POST['pesquisa'])){

    $usuario = $_POST['usuario'];
    $local = $_POST['local'];
    $projeto = $_POST['projeto'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'] ?? "0000-00-00";

    $projetoespecial = recuperaDados('projeto_especiais', 'id', $projeto);

    $projeto = $projetoespecial['projeto_especial'];

    $sql = "SELECT a.id,
	               a.nome_evento,
	               l.local,
                   ci.classificacao_indicativa,
                   s.subprefeitura,
                   o.valor_ingresso,
                   a.quantidade_apresentacao,
                   a.ficha_tecnica
            FROM agendoes AS a
            INNER JOIN agendao_ocorrencias AS o ON o.origem_ocorrencia_id = a.id
            INNER JOIN locais AS l ON l.id = o.local_id
	        INNER JOIN subprefeituras AS s ON s.id = o.subprefeitura_id
            INNER JOIN classificacao_indicativas AS ci ON ci.id = a.classificacao_indicativa_id
            INNER JOIN usuarios AS u ON a.usuario_id = u.id 
            INNER JOIN projeto_especiais AS pe ON a.projeto_especial_id = pe.id
            WHERE a.evento_status_id = 3 AND a.publicado = 1 AND o.data_inicio = '$data_inicio' 
              AND o.data_fim = '$data_fim' AND u.nome_completo = '$usuario' 
              AND pe.projeto_especial = '$projeto'";

    $query = mysqli_query($con, $sql);
}

?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header">Produção - Exportar para Excel</h3>
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Resumo da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblResultadoAgendao" class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Local do Evento</th>
                        <th>Classificação Indicativa</th>
                        <th>Subprefeitura</th>
                        <th>Valor do Ingresso</th>
                        <th>Nº de Atividades</th>
                        <th>Artistas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($agendao = mysqli_fetch_array($query)) {
                        $_SESSION['idAgendaoProd'] = $agendao['id'];
                        ?>
                        <tr>
                            <td><?= $agendao['nome_evento'] ?></td>
                            <td><?= $agendao['local'] ?></td>
                            <td><?= $agendao['classificacao_indicativa'] ?></td>
                            <td><?= $agendao['subprefeitura'] ?></td>
                            <td><?= $agendao['valor_ingresso'] == '0.00' ? 'Grátis' : 'R$ ' . dinheiroParaBr($agendao['valor_ingresso']) ?></td>
                            <td><?= $agendao['quantidade_apresentacao'] ?></td>
                            <td><?= $agendao['ficha_tecnica'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Local do Evento</th>
                        <th>Classificação Indicativa</th>
                        <th>Subprefeitura</th>
                        <th>Valor do Ingresso</th>
                        <th>Nº de Atividades</th>
                        <th>Artistas</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=producao&p=agendoes&sp=pesquisa">
                    <button type="button" class="btn btn-default">Voltar para a pesquisa</button>
                </a>
                <a href="../pdf/exporta_excel_agendao_producao.php">
                    <button type="button" class="btn btn-success pull-right">Exportar para Excel</button>
                </a>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblResultadoAgendao').DataTable({
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