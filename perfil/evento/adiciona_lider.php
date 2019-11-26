<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();


if (isset($_POST['cadastraLider'])) {
    $idPessoa = $_POST['idPf'];
}

if (isset($_POST['gravar'])){
    $nome = addslashes($_POST['nome']);
    $nomeArtistico = addslashes($_POST['nomeArtistico']);
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $drt = $_POST['drt'];
}
if (isset($_POST['gravar'])) {
    $mensagem = "";
    $idPessoa = $_POST['idPf'];
    $sqlUpdate = "UPDATE siscontrat.`pessoa_fisicas` SET 
                   nome = '$nome',
                   nome_artistico = '$nomeArtistico',
                   email = '$email',
                   WHERE id = '$idPessoa'";

    if (mysqli_query($con, $sqlUpdate)) {
        //edita / insere ( caso não tenha )  os telefones
        if (isset($_POST['telefone2'])) {
            $telefone2 = $_POST['telefone2'];
            $sqlTelefone2 = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone) VALUES ('$idPessoa', '$telefone2')";
            $query = mysqli_query($con, $sqlTelefone2);
            gravarLog($sqlTelefone2);
        }
        if (isset($_POST['telefone3'])) {
            $telefone3 = $_POST['telefone3'];
            $sqlTelefone3 = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone) VALUES ('$idPessoa', '$telefone3')";
            $query = mysqli_query($con, $sqlTelefone3);
            gravarLog($sqlTelefone3);
        }
        foreach ($telefones as $idTelefone => $telefone) {
            if (!strlen($telefone)) {
                $sqlDelete = "DELETE FROM pf_telefones WHERE id = '$idTelefone'";
                mysqli_query($con, $sqlDelete);
                gravarLog($sqlDelete);
            }
            if ($telefone != '') {
                $sqlTelefone = "UPDATE  pf_telefones SET telefone = '$telefone' WHERE id = '$idTelefone'";
                mysqli_query($con, $sqlTelefone);
                gravarLog($sqlTelefone);
            }
        }
        if ($drt != NULL) {
            $drt_existe = verificaExiste("drts", "pessoa_fisica_id", $idPessoa, 0);
            if ($drt_existe['numero'] > 0) {
                $sqlNit = "UPDATE drts SET drt = '$drt' WHERE pessoa_fisica_id = '$idPessoa'";
                if (!mysqli_query($con, $sqlNit)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlNit;
                }
            }
        }
        $mensagem .= mensagem("success", "Gravado com sucesso!");
    } else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

$pf = recuperaDados("pessoa_fisicas","id",$idPessoa);
$drts = recuperaDados("drts", "pessoa_fisica_id", $idPessoa);

$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = $idPessoa";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();
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
        <h2 class="page-header">ARTISTA - Líder do Grupo ou Artista Solo</h2>
        <h4>No caso de espetáculos de teatro, dança e circo, este deve ser do elenco ou o diretor do espetáculo e deve
            ter DRT. No caso espetáculo de música, este deve ser um músico do espetáculo</h4>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->

                <div class="box box-info">
                    <div class="box-header">
                        <form>
                            <a href="?perfil=evento&p=pf_pesquisa" type="submit" name="trocaArtista"
                               class="btn btn-info pull-left">TROCAR O ARTISTA
                            </a>
                        </form>
                    </div>
                    <div class="row" align="center">
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=adiciona_lider" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120'
                                       value='<?= $pf ['nome'] ?>'>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nomeArtistico">Nome Artístico:</label>
                                    <input type='text' class='form-control' id='nomeArtistico' name='nomeArtistico'
                                           maxlength='15' value='<?= $pf ['nome_artistico'] ?>' required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <input type='email' class='form-control' id='email' name='email' maxlength='50'
                                           placeholder='Digite o e-mail' value='<?= $pf ['email'] ?>' required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="telefone">Telefone #1 * </label>
                                    <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" required
                                           class="form-control"
                                           id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
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
                                <div class="form-group col-md-2">
                                    <label for="recado">Telefone #3</label>
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
                                <div class="form-group col-md-3">
                                    <label for="drts">DRT: <i>(Somente para artes cênicas)</i></label>
                                    <input type="text" class="form-control" id='drt' name='drt'
                                           onkeyup="mascara( this, mtel );" maxlength="15"
                                           value='<?= $drts ['drt'] ?>'>
                                </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="idPf" value="<?= $idPessoa ?>">
                            <button type="submit" name="gravar" id="gravar" class="btn btn-info pull-right">Salvar</button>
                            <a href="?perfil=evento&p=pf_pesquisa">
                                <button type='button' class='btn btn-default'>Voltar</button>
                                <a>
                        </div>
                    </form>
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