<?php
include "includes/menu_principal.php";
$con = bancoMysqli();

if (isset($_POST['filtrar'])) {
    $datainicio = $_POST['data_inicio'] ?? null;
    $datafim = $_POST['data_fim'] ?? null;
    $local = $_POST['local'] ?? null;
    $usuario = $_POST['inserido'] ?? null;
    $projeto = $_POST['projetoEspecial'] ?? null;

    $sqlLocal = '';
    $sqlUsuario = '';
    $sqlProjeto = '';
    $sqlInicio = '';
    $sqlFim = '';

    if ($local != null)
        $sqlLocal = " AND o.local_id = '$local'";

    if ($usuario != null)
        $sqlUsuario = " AND u.nome_completo = '$usuario'";

    if ($projeto != null)
        $sqlProjeto = " AND a.projeto_especial_id = '$projeto'";

    if ($datainicio != null)
        $sqlInicio = " AND o.data_inicio >= '$datainicio'";

    if ($datafim != null)
        $sqlFim = " AND o.data_fim <= '$datafim'";

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
                   $sqlUsuario $sqlProjeto $sqlLocal $sqlInicio $sqlFim
                   GROUP BY a.id";

    $queryAgendao = mysqli_query($con, $sqlAgendao);
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3 class="page-title">Agendão</h3>
        </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Resultado da pesquisa</h3>    
                </div>
                <div class="box">
                    <div class="box-body">
                        <table id="tblAgendao" class="table table-bordered table-striped table-responsive">
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
                                         <form method="post" target="_blank" action="../pdf/exportar_excel_agendao.php">
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
                    <a href="?perfil=agendao&p=pesquisa_exporta">
                        <button type="button" class="btn btn-default center-block btn-block" style="width:20%">Nova pesquisa</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAgendao').DataTable({
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