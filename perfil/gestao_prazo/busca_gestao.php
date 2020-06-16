<?php
$con = bancoMysqli();

if (isset($_POST['aprovar'])) {
    $idEvento = $_POST['idEvento'];
    $evento = recuperaDados('eventos', 'id', $idEvento);
    $sqlAprova = "UPDATE pedidos SET status_pedido_id = 2 WHERE origem_id = '$idEvento'";
    if (mysqli_query($con, $sqlAprova)) {
        $data = date("Y-m-d H:i:s", strtotime("-3 hours"));
        $sqlEnvia = "INSERT INTO evento_envios (evento_id, data_envio) VALUES ('$idEvento', '$data')";
        mysqli_query($con, $sqlEnvia);
        $idUser = $_SESSION['usuario_id_s'];
        $sqlEnvio = "INSERT INTO producao_eventos (evento_id, usuario_id, data) VALUES ('$idEvento','$idUser','$data')";
        mysqli_query($con, $sqlEnvio);
        $mensagem = mensagem("success", "Evento aprovado com sucesso!");

        if ($evento['tipo_evento_id'] == 1) {
            $protocolo = geraProtocolo($idEvento) . "-E";
        } else if ($evento['tipo_evento_id'] == 2) {
            $protocolo = geraProtocolo($idEvento) . "-C";
        }
        if ($evento['contratacao'] == 1) {
            $sqlEnviaEvento = "UPDATE eventos SET protocolo = '$protocolo', evento_status_id = 3 WHERE id = '$idEvento'";
            mysqli_query($con, $sqlEnviaEvento);
        }

    }
}

if (isset($_POST['vetar'])) {
    $idEvento = $_POST['idEvento'];
    $sqlVeta = "UPDATE eventos SET evento_status_id = 6 WHERE id = '$idEvento'";
    if (mysqli_query($con, $sqlVeta)) {
        $sqlVeta = "UPDATE pedidos SET status_pedido_id = 3 WHERE origem_id = '$idEvento'";
        $queryVeta = mysqli_query($con, $sqlVeta);
        $motivo = $_POST['motivo'];
        $justificativa = $_POST['justificativa'];
        $titulo = "Motivo da Não Aprovação: " . $_POST['titulo'];
        $idUser = $_SESSION['usuario_id_s'];
        $data = $data = date("Y-m-d H:i:s", strtotime("-3 hours"));
        $sqlChamado = "INSERT INTO chamados (evento_id, 
                                             chamado_tipo_id, 
                                             titulo, 
                                             justificativa, 
                                             usuario_id, 
                                             data) 
                                        VALUES(
                                            '$idEvento',
                                            '$motivo',   
                                            '$titulo',
                                            '$justificativa',
                                            '$idUser',
                                            '$data')";
        $queryChamado = mysqli_query($con, $sqlChamado);
        $mensagem = mensagem("success", "Evento vetado com sucesso!");
    }
}

$sql = "SELECT e.id, 
        e.nome_evento,
		fiscal.nome_completo AS 'fiscal',
        e.usuario_id
        FROM eventos as e
        INNER JOIN usuarios AS fiscal ON e.fiscal_id = fiscal.id
        WHERE e.evento_status_id = 2 AND e.publicado = 1";

$query = mysqli_query($con, $sql);
?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Eventos - Gestão</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de eventos fora do prazo</h3>
                    </div>
                    <div class="row" align="center">
                        <?php
                        if (isset($mensagem)) {
                            echo $mensagem;
                        };
                        ?>
                    </div>
                    <div class="box-body">
                        <table id="tblGestao" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Locais</th>
                                <th>Período</th>
                                <th>Fiscal</th>
                                <th>Operador</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($eventos = mysqli_fetch_array($query)) {
                                $idEvento = $eventos['id'];

                                //locais
                                $sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = '$idEvento' AND o.publicado = 1";
                                $queryLocal = mysqli_query($con, $sqlLocal);
                                $local = '';
                                while ($locais = mysqli_fetch_array($queryLocal)) {
                                    $local = $local . '; ' . $locais['local'];
                                }
                                $local = substr($local, 1);

                                //operador
                                $testaOperador = $con->query("SELECT operador_id FROM pedidos WHERE origem_id = $idEvento")->fetch_array();
                                $idOperador = $testaOperador['operador_id'] ?? NULL;
                                if ($idOperador > 0) {
                                    $operador = $con->query("SELECT u.nome_completo FROM usuarios AS u INNER JOIN usuario_contratos uc ON u.id = uc.usuario_id WHERE u.id = $idOperador AND usuario_id = $idOperador")->fetch_array()['nome_completo'];
                                }else{
                                    $operador = "Não cadastrado";
                                }
                                echo "<tr>";
                                echo "<td>" . $eventos['nome_evento'] . "</td>";
                                echo "<td>" . $local . "</td>";
                                echo "<td>" . retornaPeriodoNovo($eventos['id'], 'ocorrencias') . "</td>";
                                echo "<td>" . $eventos['fiscal'] . "</td>";
                                echo "<td>" . $operador . "</td>";
                                echo "<td>
                                                <form method='POST' action='?perfil=gestao_prazo&p=detalhes_gestao' role='form'>
                                                <input type='hidden' name='idEvento' value='" . $eventos['id'] . "'>
                                                <button type='submit' name='carregar' class='btn btn-block btn-primary'><i class='fa fa-file-text-o'></i> </button>
                                                </form>
                                        </td>";
                            }
                            echo "</tbody>"
                            ?>
                        </table>
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
        $('#tblGestao').DataTable({
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