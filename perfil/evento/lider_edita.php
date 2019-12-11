<?php
$con = bancoMysqli();
$conn = bancoPDO();


if (isset($_POST['idLider'])) {
    $idLider = $_POST['idLider'];
}


if (isset($_POST['cadastrar']) || isset($_POST['editar'])) {
    $idPedido = $_SESSION['idPedido'];
    $nome = addslashes($_POST['nome']);
    $nomeArtistico = $_POST['nomeArtistico'];
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $drt = $_POST['drt'];
    $data = date("y-m-d h:i:s");
    $passaporte = $_POST['passaporte'] ?? NULL;
    $cpf = $_POST['cpf'] ?? NULL;
    $tipoDocumento = $_POST['tipoDocumento'];
    $idAtracao = $_POST['idAtracao'];
}

if (isset($_POST['editar'])) {
    $idLider = $_POST['idLider'];
    $sqlUpdate = "UPDATE pessoa_fisicas SET
                   nome = '$nome',
                   nome_artistico = '$nomeArtistico',
                   email = '$email',
                   passaporte = '$passaporte',
                   cpf = '$cpf'
                   WHERE id = '$idLider'";


    if (mysqli_query($con, $sqlUpdate)) { // --> Query dos UPDATES
        if (isset($_POST['cpf'])) {
            $cpf = $_POST['cpf'];
            $sqlCpf = "UPDATE pessoa_fisicas SET cpf = '$cpf' WHERE id = '$idLider'";
        }
        if (isset($_POST['passaporte'])) {
            $passaporte = $_POST['passaporte'];
            $sqlPassaporte = "UPDATE pessoa_fisicas SET passaporte = '$passaporte' WHERE id = '$idLider'";
        }
        if (isset($_POST['telefone2'])) {
            $telefone2 = $_POST['telefone2'];
            $sqlTelefone2 = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone)VALUES ('$idLider','$telefone2')";
            $queryTelefone2 = mysqli_query($con, $sqlTelefone2);
            gravarLog($sqlTelefone2); // > 		//grava na tabela log os inserts e updates
        }

        if (isset($_POST['telefone3'])) {
            $telefone3 = $_POST['telefone3'];
            $sqlTelefone3 = "INSERT INTO pf_telefones (pessoa_fisica_id,telefone) VALUES ('$idLider','$telefone3')";
            $queryTelefone3 = mysqli_query($con, $sqlTelefone3);
            gravarLog($sqlTelefone3); // > 		//grava na tabela log os inserts e updates
        }

        foreach ($telefones AS $idTelefone => $telefone) {
            if (!strlen($telefone)) { // -> Determina o tamanho de uma string.
                $sqlDeleteTel = "DELETE FROM pf_telefones WHERE id = '$idTelefone'";
                mysqli_query($con, $sqlDeleteTel);
                gravarLog($sqlDeleteTel);
            }
            if ($telefone != '') {
                $sqlTelefone = "UPDATE pf_telefones SET telefone = '$telefone' WHERE id = '$idTelefone'";
                mysqli_query($con, $sqlTelefone);
                gravarLog($sqlTelefone);
            }
            if ($drt != NULL) {
                $drt_existe = verificaExiste("drts", "pessoa_fisica_id", $idLider, 0);
                if ($drt_existe ['numero'] > 0) {
                    $sqldrt = "UPDATE drts SET drt = '$drt' WHERE pessoa_fisica_id = '$idLider'"; // -> Caso for maior que 0 ele ele retorna erro .
                    if (!mysqli_query($con, $sqldrt)) {
                        $mensagem .= mensagem("danger", "Erro ao gravar! Tente Novamente. ! ") . $sqldrt;
                    }
                } else {
                    $sqldrt = "INSERT INTO drts (pessoa_fisica_id, drt , publicado) VALUES ('$idLider', '$drt', 1)";
                    if (!mysqli_query($con, $sqldrt)) {
                        $mensagem .= mensagem("danger", "Erro ao gravar! Insira um lider para poder finalizar ") . $sqldrt;
                    }
                }
            }
        }
        $mensagem .= mensagem("success", "Atualizado com sucesso!");
    } else {
        //$mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}


if (isset($_POST['cadastrar'])) {
    $mensagem = "";
    $sqlInsert = "INSERT INTO pessoa_fisicas (nome, nome_artistico, email , ultima_atualizacao, passaporte, cpf) VALUES ('$nome','$nomeArtistico','$email','$data','$passaporte','$cpf')";

    if (mysqli_query($con, $sqlInsert)) {
        $idLider = recuperaUltimo("pessoa_fisicas");
        $sqLider = "INSERT INTO lideres (pedido_id, atracao_id, pessoa_fisica_id) 
                                VALUES ('$idPedido', '$idAtracao', '$idLider')";
        mysqli_query($con, $sqLider);

        foreach ($telefones AS $telefone) {
            if (!empty($telefone)) {
                $sqlTelefone = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone, publicado) VALUES ('$idLider','$telefone',1)";
                mysqli_query($con, $sqlTelefone);
            }
        }
        if ($drt != NULL) {
            $sqlDRT = "INSERT INTO drts (pessoa_fisica_id, drt, publicado)  VALUES ('$idLider','$drt',1)";
            if (!mysqli_query($con, $sqlDRT)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlDRT;
            }
        }
        $mensagem .= mensagem("success", "Cadastro realizado com sucesso");
    } else {
        $mensagem .= mensagem("danger", "Erro ao cadastrar");
    }
}

if (isset($_POST['selecionar'])) {
    $idLider = $_POST['idLider'];
    $idPedido = $_POST['idPedido'];
    $idAtracao = $_POST['idAtracao'];
    $tipoDocumento = $_POST['tipoDocumento'];
}

$lider = recuperaDados("pessoa_fisicas", "id", $idLider);
$drt = recuperaDados("drts", "pessoa_fisica_id", $idLider);

$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idLider'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();
include "includes/menu_interno.php";
?>

<script language="JavaScript">
    function barraData(n) {
        if (n.value.length == 2)
            c.value += '/';
        if (n.value.length == 5)
            c.value += '/';
    }
</script>


<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Edição de Líder</h2>
        <div class="row">
            <div class="box-header">
                <a href="?perfil=evento&p=pesquisa_lider">
                    <button type="submit" name="trocaArtista" class="btn btn-info pull-left">TROCAR O ARTISTA</button>
                </a>
            </div>
            <div class="col-md-12">
                <div class="row" align="center">
                    <?= $mensagem ?? NULL; ?>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Líder do Evento</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($resultado)) {
                            echo $resultado;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=lider_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: *</label>
                                    <input type='text' class='form-control' id='nome' name='nome' maxlength='120'
                                           value="<?= $lider['nome'] ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome Artístico:</label>
                                    <input type='text' class='form-control' id='nomeArtistico' name='nomeArtistico'
                                           maxlength='120'
                                           value="<?= $lider['nome_artistico'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="tipoDocumento" value='<?= $tipoDocumento ?>'>
                                <?php
                                if ($tipoDocumento == 1) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="cpf">CPF</label>
                                        <input type='text' class='form-control' id='cpf' name='cpf' maxlength='60'
                                               value=<?= $lider['cpf'] ?> readonly>
                                    </div>
                                <?php } else if ($tipoDocumento == 2) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="passaporte"> Passaporte</label>
                                        <input type='text' class='form-control' id='passaporte' name='passaporte'
                                               value="<?= $lider['passaporte'] ?>" required maxlength="8" readonly>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type='text' class='form-control' id='email' name='email'
                                           required maxlength="8" value="<?= $lider['email'] ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="celular">Telefone #1</label>
                                    <input type="text" class="form-control"
                                           id='telefone' name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           required maxlength="11" value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone1" name="telefone1">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #3</label>
                                    <?php if (isset($arrayTelefones[2])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>

                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="drt">DRT: <i>(Somente para artes cênicas)</i></label>
                                        <input type="text" class="form-control" id='drt' name='drt'
                                               maxlength="15" value="<?= $drt['drt'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="idLider" value="<?= $_POST['idLider'] ?>">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="idAtracao" value="<?= $_POST['idAtracao'] ?>">
                            <button type="submit" name="editar" class="btn btn-info pull-right">Salvar</button>
                    </form>
                    <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                        <input type="hidden" name="idLider" value="<?= $_POST['idLider'] ?>">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <input type="hidden" name="idAtracao" value="<?= $_POST['idAtracao'] ?>">

                        <button type="submit" name="adicionaLider" class="btn btn-info pull-left">Ir ao pedido de
                            contratação
                        </button>
                    </form>
                </div>
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