<?php
include "includes/menu_principal.php";
$con = bancoMysqli();

if (isset($_POST['cadastraLocal'])) {
    $idInstituicao = $_POST['instituicao'] ?? NULL;
    $local = addslashes($_POST['local']);
    $cep = $_POST['cep'];
    $rua = addslashes($_POST['rua']);
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? NULL;
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $estado = addslashes($_POST['estado']);
    $zona = addslashes($_POST['zona']);

    $existe = 0;
    $sqLocais = "SELECT * FROM locais WHERE instituicao_id = '$idInstituicao'";
    $queryLocais = mysqli_query($con, $sqLocais);
    while ($locais = mysqli_fetch_array($queryLocais)) {
        if ($locais['local'] == $local) {
            $existe = 1;
        }
    }

    if ($existe != 0) {

        echo "<div id='resposta'>0</div>";

    } else {
        $sql = "INSERT INTO locais (instituicao_id, local, logradouro, numero, complemento, bairro, cidade, uf, cep, zona_id, publicado)
                VALUES ('$idInstituicao', '$local', '$rua', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$cep', '$zona', 2)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            echo "<div id='resposta'>1</div>";

        } else {
            echo "<div id='resposta'>2</div>";
        }
    }
}

if (isset($_POST['cadastraEspaco'])) {
    $idLocal = $_POST['local'];
    $espaco = $_POST['espaco'];

    $existe = 0;
    $sqlEspacos = "SELECT * FROM espacos WHERE local_id = '$idLocal'";
    $queryEspacos = mysqli_query($con, $sqlEspacos);
    while ($espacos = mysqli_fetch_array($queryEspacos)) {
        if ($espacos['espaco'] == $espaco) {
            $existe = 1;
        }
    }

    if ($existe != 0) {
        echo "<div id='resposta'>0</div>";

    } else {

        $sql = "INSERT INTO espacos (local_id ,espaco, publicado)
                VALUES ('$idLocal', '$espaco', 2)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            echo "<div id='resposta'>1</div>";

            //$mensagem2 = mensagem("success", "Solicitação de adição de espaço efetuado com sucesso");
        } else {
            echo "<div id='resposta'>2</div>";
           // $mensagem2 = mensagem("danger", "Erro na solicitação de adição de espaço! Tente novamente.");
        }
    }

}

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$idUsuario = $_SESSION['idUser'];


$idUser = $_SESSION['idUser'];
$sql = "SELECT * FROM eventos WHERE publicado = 1 AND agendao = 0 AND evento_status_id >= 3 AND (suplente_id = '$idUsuario' OR fiscal_id = '$idUsuario' OR usuario_id = '$idUsuario') ORDER BY id DESC LIMIT 0,20";

$query = mysqli_query($con, $sql);
$linha = mysqli_num_rows($query);

if ($linha >= 1) {
    $tem = 1;
} else {
    $tem = 0;
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START ACCORDION-->
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <h2 class="page-header">Seus últimos eventos enviados</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="box-group" id="accordionEventos">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <?php
                            if ($tem == 0) {
                                $mensagem = mensagem("info", "Não existe eventos enviados!");
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    $locais = listaLocais($evento['id'], '1');
                                    ?>
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordionEventos"
                                                   href="#collapse<?= $evento['id'] ?>">
                                                    <?= $evento['nome_evento'] ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?= $evento['id'] ?>" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <?php
                                                $dataEnvio = recuperaDados('evento_envios', 'evento_id', $evento['id']);
                                                $usuario = recuperaDados('usuarios', 'id', $evento['usuario_id']);
                                                ?>
                                                <p><b>Enviado por: </b><?= $usuario['nome_completo'] ?>
                                                    <b>em:</b> <?= exibirDataBr($dataEnvio['data_envio']) ?> </p>
                                                <p><b>Período:</b> <?= retornaPeriodoNovo($evento['id']) ?> </p>
                                                <p><b>Local:</b> <?= $locais ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="row" align="center">
                                <?php if (isset($mensagem)) {
                                    echo $mensagem;
                                }; ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>
