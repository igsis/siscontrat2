<?php
$con = bancoMysqli();

if(isset($_POST['pesquisa'])){

    $usuario = $_POST['usuario'];
    $local = $_POST['local'];
    $projeto = $_POST['projeto'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];

    $sql = "SELECT a.id,
	               a.nome_evento,
	               l.local,
                   ci.classificacao_indicativa,
                   s.subprefeitura,
                   o.valor_ingresso,
                   a.quantidade_apresentacao,
                   a.ficha_tecnica
            FROM agendoes as a
            INNER JOIN agendao_ocorrencias as o on o.origem_ocorrencia_id = a.id
            INNER JOIN locais as l on l.id = o.local_id
	        INNER JOIN subprefeituras as s on s.id = o.subprefeitura_id
            INNER JOIN classificacao_indicativas as ci on ci.id = a.classificacao_indicativa_id
            WHERE a.evento_status_id = 3 AND a.publicado = 1 AND o.data_inicio = '$data_inicio' AND o.data_fim = '$data_fim'";
    $query = mysqli_query($con, $sql);
}

?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header">Produção - Exportar para Excel</h3>
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Resumo da pesquisa eventos Agendão</h3>
            </div>
            <div class="box-body">
                <table id="tblAgendao" class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Local do Evento</th>
                        <th>Classificação indicativa</th>
                        <th>SubPrefeitura</th>
                        <th>Valor do ingresso</th>
                        <th>Nº de atividades</th>
                        <th>Artistas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($agendao = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?= $agendao['nome'] ?></td>
                            <td><?= $agendao['nome_local'] ?></td>
                            <td><?= $agendao['classificacao'] ?></td>
                            <td><?= $agendao['subprefeitura'] ?></td>
                            <td><?= $agendao['valor_ingresso'] == '0.00' ? 'Grátis' : 'R$ ' . dinheiroParaBr($linha['valor_ingresso']) ?></td>
                            <td><?= $agendao['apresentacoes'] ?></td>
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
                        <th>Classificação indicativa</th>
                        <th>SubPrefeitura</th>
                        <th>Valor do ingresso</th>
                        <th>Nº de atividades</th>
                        <th>Artistas</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>
