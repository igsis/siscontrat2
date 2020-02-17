<?php
include "includes/menu_principal.php";

unset($_SESSION['idPedido']);

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
                VALUES ('$idInstituicao', '$local', '$rua', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$cep', '$zona', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem2 = mensagem("success", "Adição de local efetuado com sucesso");
            echo "<div id='resposta'>1</div>";

        } else {
            $mensagem2 = mensagem("danger", "Erro na adição de local! Tente novamente.");
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
                VALUES ('$idLocal', '$espaco', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            echo "<div id='resposta'>1</div>";

            $mensagem2 = mensagem("success", "Adição de espaço efetuado com sucesso");
        } else {
            echo "<div id='resposta'>2</div>";
            $mensagem2 = mensagem("danger", "Erro na adição de espaço! Tente novamente.");
        }
    }

}

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$idUsuario = $_SESSION['idUser'];


$idUser = $_SESSION['idUser'];
$sql = "SELECT e.id, e.nome_evento, u.nome_completo, ee.data_envio, e.usuario_id, e.suplente_id, e.fiscal_id FROM eventos e 
                INNER JOIN pedidos p ON p.origem_id = e.id 
                INNER JOIN usuarios u on e.usuario_id = u.id
                INNER JOIN evento_envios ee on e.id = ee.evento_id
                WHERE e.publicado = 1 AND p.publicado = 1 AND e.evento_status_id >= 3 
                AND p.origem_tipo_id = 1 AND p.status_pedido_id = 2 
                AND (e.suplente_id = '$idUsuario' OR e.fiscal_id = '$idUsuario' OR e.usuario_id = '$idUsuario') 
                AND ee.data_envio is not null 
                ORDER BY e.id DESC LIMIT 0,1";

$query = mysqli_query($con, $sql);
$linha = mysqli_num_rows($query);

if ($linha >= 1) {
    $tem = 1;
} else {
    $tem = 0;
}

?>

<div class="content-wrapper">
    <section class="content">
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <h2 class="page-header">Seus últimos eventos enviados</h2>
        <p>
            <small class="label pull-right bg-yellow-active">Suplente</small>
            <small class="label pull-right bg-primary">Fiscal</small>
            <small class="label pull-right bg-green-active">Usuário</small>
        </p>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="box-group" id="accordionEventos">
                            <?php
                            if ($tem == 0) {
                                $mensagem = mensagem("info", "Não existe eventos enviados!");
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    $locais = listaLocais($evento['id'], '1');

                                    if ($evento['fiscal_id'] == $idUser)
                                        $corRepresentativa = 'box-primary';
                                    else if ($evento['suplente_id'] == $idUser)
                                        $corRepresentativa = 'box-warning';
                                    else if ($evento['usuario_id'] == $idUser)
                                        $corRepresentativa = 'box-success';

                                    ?>
                                    <div class="panel box <?= $corRepresentativa ?>">
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
                                                <p><b>Enviado por: </b><?= $evento['nome_completo'] ?>
                                                    <b>em:</b> <?= exibirDataHoraBr($evento['data_envio']) ?> </p>
                                                <p><b>Período:</b> <?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?> </p>
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
                </div>
            </div>
        </div>
    </section>
</div>
