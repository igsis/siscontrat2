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
        $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";

    $sql = "SELECT e.id,
	               e.nome_evento,
                   e.tipo_evento_id,
	               l.local,
                   ci.classificacao_indicativa,
                   s.subprefeitura,
                   o.valor_ingresso,
                   a.quantidade_apresentacao,
                   a.ficha_tecnica
            FROM eventos AS e
            INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id
            INNER JOIN locais AS l ON l.id = o.local_id
            INNER JOIN atracoes a on e.id = a.evento_id    
	        INNER JOIN subprefeituras AS s ON s.id = o.subprefeitura_id
            INNER JOIN classificacao_indicativas AS ci ON ci.id = a.classificacao_indicativa_id
            INNER JOIN usuarios AS u ON e.usuario_id = u.id 
            INNER JOIN projeto_especiais AS pe ON e.projeto_especial_id = pe.id
            WHERE e.evento_status_id = 3 AND o.publicado = 1 AND e.publicado = 1 
            AND o.data_inicio >= '$datainicio' AND o.data_fim <= '$datafim'
            $sqlUsuario $sqlProjeto $sqlLocal GROUP BY e.id";
                   

    $query = mysqli_query($con, $sql);
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
                <table id="tblResultadoEventos" class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Local do Evento</th>
                        <th>Classificação Indicativa</th>
                        <th>Subprefeitura</th>
                        <th>Valor do Ingresso</th>
                        <th>Nº de Atividades</th>
                        <th>Artistas</th>
                        <th>Exportar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($evento = mysqli_fetch_array($query)) {
                        $sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $evento['id'] ." AND o.publicado = 1";
                        $queryLocal = mysqli_query($con, $sqlLocal);
                        $local = '';
                        while ($locais = mysqli_fetch_array($queryLocal)) {
                            $local = $local . '; ' . $locais['local'];
                        }
                        $local = substr($local, 1);

                        if ($evento['tipo_evento_id'] == 2) {
                            $filme = recuperaDados("filmes", "id", $linha['evento_id']);
                            $classificao = recuperaDados("classificacao_indicativas", "id", $filme['classificacao_indicativa_id']);

                            $evento['classificacao_indicativa'] = $classificao['classificacao_indicativa'];
                        }

                        ?>
                        <tr>
                            <td><?= $evento['nome_evento'] ?></td>
                            <td><?= $local ?></td>
                            <td><?= $evento['classificacao_indicativa'] ?></td>
                            <td><?= $evento['subprefeitura'] ?></td>
                            <td><?= $evento['valor_ingresso'] == '0.00' ? 'Grátis' : 'R$ ' . dinheiroParaBr($evento['valor_ingresso']) ?></td>
                            <td><?= $evento['quantidade_apresentacao'] == '' ? 'Este evento é filme!' : $evento['quantidade_apresentacao'] ?></td>
                            <td><?= $evento['ficha_tecnica'] ?></td>
                            <td>
                                <form action="../pdf/exporta_excel_evento_producao.php" target="_blank" method="POST">
                                    <input type="hidden" value="<?=$evento['id']?>" name="idEvento">
                                    <button type="submit" class="btn btn-block btn-success">
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
                        <th>Local do Evento</th>
                        <th>Classificação Indicativa</th>
                        <th>Subprefeitura</th>
                        <th>Valor do Ingresso</th>
                        <th>Nº de Atividades</th>
                        <th>Artistas</th>
                        <th>Exportar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=producao&p=eventos&sp=pesquisa">
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
        $('#tblResultadoEventos').DataTable({
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
