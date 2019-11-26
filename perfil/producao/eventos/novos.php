<?php

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);
$idUser = $_SESSION['idUser'];
$con = bancoMysqli();


$sqlEvento = "SELECT
                    eve.id AS 'id',
                    eve.protocolo AS 'protocolo',
                    eve.nome_evento AS 'nome_evento',
                    env.data_envio AS 'data_envio',
                    u.nome_completo as 'usuario',
                    en.visualizado AS 'visualizado'
            FROM eventos AS eve
            INNER JOIN ocorrencias as o on o.id = eve.id
            INNER JOIN evento_envios as env on env.evento_id = eve.id
            INNER JOIN usuarios as u on u.id = eve.usuario_id
            INNER JOIN pedidos AS ped ON ped.origem_id = eve.id
            INNER JOIN producao_eventos AS en ON en.evento_id = eve.id 
WHERE eve.publicado = 1 AND eve.evento_status_id = 3 AND ped.status_pedido_id = 2 AND en.visualizado = 0";

$queryEvento = mysqli_query($con, $sqlEvento);

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Eventos Novos</h2>
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
                        <table id="tblEventosNovos" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Nome</th>
                                <th>Locais</th>
                                <th>Espaços</th>
                                <th>Periodo</th>
                                <th>Data do Envio</th>
                                <th>Usuário</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($eventoNovo = mysqli_fetch_array($queryEvento)) {
                            $idEvento = $eventoNovo['id'];
                            $sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = '$idEvento' AND o.publicado = 1";
                            $queryLocal = mysqli_query($con, $sqlLocal);
                            $local = '';
                            while ($locais = mysqli_fetch_array($queryLocal)) {
                                $local = $local . '; ' . $locais['local'];
                            }
                            $local = substr($local, 1);

                            $sqlEspaco = "SELECT e.espaco FROM espacos AS e INNER JOIN ocorrencias AS o ON o.espaco_id = e.id WHERE o.origem_ocorrencia_id = '$idEvento'";
                            $queryEspaco = mysqli_query($con, $sqlEspaco);
                            $espaco = '';
                            while ($espacos = mysqli_fetch_array($queryEspaco)) {
                                $espaco = $espaco . '; ' . $espacos['espaco'];
                            }
                            $espaco = substr($espaco, 1);
                            ?>
                            <tr>
                                <?php
                                echo "<td>" . $eventoNovo['protocolo'] . "</td>";
                                echo "<td>" . $eventoNovo['nome_evento'] . "</td>";
                                echo "<td>" . $local . "</td>";
                                echo "<td>" . $espaco . "</td>";
                                echo "<td>" . retornaPeriodoNovo($eventoNovo['id'], 'ocorrencias') . "</td>";
                                echo "<td>" . $eventoNovo['data_envio'] . "</td>";
                                echo "<td>" . $eventoNovo['usuario'] . "</td>";
                                echo "<td>                               
                            <form method='POST' action='?perfil=producao&p=eventos&sp=visualizacao' role='form'>
                            <input type='hidden' name='idEvento' value='" . $eventoNovo['id'] . "'>
                            <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'> </span></button>
                            </form>
                            </td>";

                                echo "</tr>";
                                }
                                echo "</tbody>";
                                ?>
                                <tfoot>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Nome do Evento</th>
                                    <th>Locais</th>
                                    <th>Espaços</th>
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
        $('#tblEventosNovos').DataTable({
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