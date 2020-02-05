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
        
    } else {
        $sql = "INSERT INTO locais (instituicao_id, local, logradouro, numero, complemento, bairro, cidade, uf, cep, zona_id, publicado)
                VALUES ('$idInstituicao', '$local', '$rua', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$cep', '$zona', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem2 = mensagem("success", "Adição de local efetuado com sucesso");
        } else {
            $mensagem2 = mensagem("danger", "Erro na adição de local! Tente novamente.");
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

    } else {

        $sql = "INSERT INTO espacos (local_id ,espaco, publicado)
                VALUES ('$idLocal', '$espaco', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem2 = mensagem("success", "Adição de espaço efetuado com sucesso");
        } else {
            $mensagem2 = mensagem("danger", "Erro na adição de espaço! Tente novamente.");
        }
    }

}

unset($_SESSION['idEvento']);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-lime-active">
                <h3 class="widget-user-username"><span style="color: black;"><b>Módulo Agendão</b></span></h3>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 border-right">
                        <div class="description-block">
                            <span class="description-header">
                                Nesse módulo é possível inserir e gerenciar eventos sem necessidade de contratação artística e que ainda não constem no sistema, editá-los e disponibilizá-los para importação no site Agendão.
                                <br/><br/>
                            </span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
    </section>
</div>
