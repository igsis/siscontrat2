<?php

$idUser = $_SESSION['usuario_id_s'];

$link_api_locais_instituicoes = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_listar_locais_instituicoes.php';

$con = bancoMysqli();

if (isset($_POST['checarEvento'])) {
    $idEvento = $_POST['idEvento'];
    $sqlView = "UPDATE producao_eventos SET visualizado = 1 WHERE evento_id = '$idEvento'";
    $queryView = mysqli_query($con, $sqlView);
    if (mysqli_query($con, $sqlView)) {
        $mensagem = mensagem("success", "Evento marcado como visualizado!");
    }
}

$sqlEvento = "SELECT
                    e.id AS 'id',
                    e.protocolo AS 'protocolo',
                    e.nome_evento AS 'nome_evento',
                    u.nome_completo as 'usuario'
            FROM eventos AS e
            INNER JOIN usuarios as u on u.id = e.usuario_id
            INNER JOIN producao_eventos AS en ON en.evento_id = e.id 
            WHERE en.visualizado = 1 AND e.publicado = 1 GROUP BY e.id";

$queryEvento = mysqli_query($con, $sqlEvento);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Eventos Verificados</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <div class="row" align="center">
                        <?php
                        if (isset($mensagem)) {
                            echo $mensagem;
                        } ?>
                    </div>

                    <div class="box-body">
                        <table id="tblEventosVerificados" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Nome</th>
                                <th>Locais</th>
                                <th>Espaços</th>
                                <th>Chamado</th>
                                <th>Periodo</th>
                                <th>Data do Envio</th>
                                <th>Usuário</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($eventoVerf = mysqli_fetch_array($queryEvento)) {

                                $sqlEspaco = "SELECT e.espaco FROM espacos AS e INNER JOIN ocorrencias AS o ON o.espaco_id = e.id WHERE o.origem_ocorrencia_id = " . $eventoVerf['id'];
                                $queryEspaco = mysqli_query($con, $sqlEspaco);
                                $numEspaco = mysqli_num_rows($queryEspaco);
                                $espaco = '';
                                if ($numEspaco):
                                    while ($espacos = mysqli_fetch_array($queryEspaco)) {
                                        $espaco = $espaco . '; ' . $espacos['espaco'];
                                    }

                                    $espaco = substr($espaco, 1);
                                else:
                                    $espaco = "";
                                endif;
                                $queryData = $con->query("SELECT data_envio FROM evento_envios WHERE evento_id = " . $eventoVerf['id'])->fetch_assoc();
                                $dataEnvio = $queryData['data_envio'] ?? NULL;
                                ?>

                                <tr>
                                    <td><?= $eventoVerf['protocolo'] ?></td>
                                    <td><?= $eventoVerf['nome_evento'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-block" id="exibirLocais"
                                                data-toggle="modal" data-target="#modalLocais_Inst" data-name="local"
                                                onClick="exibirLocal_Instituicao('<?= $link_api_locais_instituicoes ?>', '#modalLocais_Inst', '#modalTitulo')"
                                                data-id="<?= $eventoVerf['id'] ?>"
                                                name="exibirLocais">
                                            Ver locais
                                        </button>
                                    </td>
                                    <td><?= $espaco == NULL ? "Não possuí" : $espaco?></td>
                                    <?= retornaChamadosTD($eventoVerf['id']) ?>
                                    <td><?= retornaPeriodoNovo($eventoVerf['id'], 'ocorrencias') ?></td>
                                    <td><?= exibirDataBr($dataEnvio) ?></td>
                                    <td><?= $eventoVerf['usuario'] ?></td>
                                    <td>
                                        <form method='POST' action='?perfil=producao&p=eventos&sp=visualizacao'
                                              role='form'>
                                            <input type='hidden' name='idEvento' value="<?=$eventoVerf['id']?>">
                                            <button type='submit' name='carregar'
                                                    class='btn btn-block btn-primary'><span
                                                        class='glyphicon glyphicon-eye-open'> </span></button>
                                        </form>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Nome do Evento</th>
                                <th>Locais</th>
                                <th>Espaços</th>
                                <th>Chamado</th>
                                <th>Periodo</th>
                                <th>Data do Envio</th>
                                <th>Usuário</th>
                                <th>Visualizar</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=producao">
                            <button type="button" class="btn btn-default">Voltar</button>
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
        $('#tblEventosVerificados').DataTable({
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
