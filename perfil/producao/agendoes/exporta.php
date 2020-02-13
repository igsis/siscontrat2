<?php
$con = bancoMysqli();

if(isset($_POST['pesquisa'])){

    $datainicio = $_POST['data_inicio'] ?? NULL;
    $datafim = $_POST['data_fim'] ?? null;
    $local = $_POST['local'] ?? null;
    $usuario = $_POST['usuario'] ?? null;
    $projeto = $_POST['projeto'] ?? null;

    $sqlLocal = '';
    $sqlUsuario = '';
    $sqlProjeto = '';

    if ($local != null)
        $sqlLocal = " AND l.local = '$local'";

    if ($usuario != null)
        $sqlUsuario = " AND u.nome_completo = '$usuario'";

    if ($projeto != null)
        $sqlProjeto = " AND a.projeto_especial_id = '$projeto'";

    $sqlAgendao = "SELECT a.id, a.nome_evento, c.classificacao_indicativa,
                          s.subprefeitura, o.valor_ingresso,
                          a.quantidade_apresentacao, a.ficha_tecnica
                   FROM agendoes AS a
                   INNER JOIN agendao_ocorrencias AS o ON o.origem_ocorrencia_id = a.id
                   INNER JOIN classificacao_indicativas AS c ON a.classificacao_indicativa_id = c.id
                   INNER JOIN subprefeituras AS s ON o.subprefeitura_id = s.id
                   INNER JOIN usuarios AS u ON a.usuario_id = u.id
                   INNER JOIN projeto_especiais AS pe ON a.projeto_especial_id = pe.id
                   INNER JOIN locais AS l ON o.local_id = l.id
                   WHERE a.publicado = 1 AND o.publicado = 1 AND a.evento_status_id = 3
                   AND o.data_inicio >= '$datainicio' AND o.data_fim <= '$datafim'
                   $sqlUsuario $sqlProjeto $sqlLocal
                   GROUP BY a.id";

    $queryAgendao = mysqli_query($con, $sqlAgendao);
}

?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header">Produção - Exportar para Excel</h3>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Resumo da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblResultadoAgendao" class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Local</th>
                        <th>Classificação Indicativa</th>
                        <th>Subprefeitura</th>
                        <th>Valor do ingresso</th>
                        <th>Nº de atividades</th>
                        <th>Artistas</th>
                        <th>Exportar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($agendao = mysqli_fetch_array($queryAgendao)) {
                        $sqlLocal = "SELECT l.local FROM locais l INNER JOIN agendao_ocorrencias ao ON ao.local_id = l.id WHERE ao.origem_ocorrencia_id = " . $agendao['id'] . " AND ao.publicado = 1";
                        $queryLocal = mysqli_query($con, $sqlLocal);
                        $local = '';
                        while ($locais = mysqli_fetch_array($queryLocal)) {
                            $local = $local . '; ' . $locais['local'];
                        }
                        $local = substr($local, 1);
                        ?>
                        <tr>
                            <td><?= $agendao['nome_evento'] ?></td>
                            <td><?= $local ?></td>
                            <td><?= $agendao['classificacao_indicativa'] ?></td>
                            <td><?= $agendao['subprefeitura'] ?></td>
                            <td><?= $agendao['valor_ingresso'] == '0.00' ? 'Grátis' : 'R$ ' . dinheiroParaBr($agendao['valor_ingresso']) ?></td>
                            <td><?= $agendao['quantidade_apresentacao'] ?></td>
                            <td><?= $agendao['ficha_tecnica'] ?></td>
                            <td>
                                 <form method="post" target="_blank" action="../pdf/exporta_excel_agendao_producao.php">
                                      <input type="hidden" name="idAgendao" value="<?=$agendao['id']?>">
                                      <button type="submit" class="btn btn-success btn-theme btn-block">
                                         <span class="glyphicon glyphicon-list-alt"></span>
                                      </button>
                                 </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Local</th>
                        <th>Classificação Indicativa</th>
                        <th>Subprefeitura</th>
                        <th>Valor do ingresso</th>
                        <th>Nº de atividades</th>
                        <th>Artistas</th>
                        <th>Exportar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=producao&p=agendoes&sp=pesquisa">
                    <button type="button" class="btn btn-default btn-block center-block" style="width:30%">Voltar para a pesquisa</button>
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