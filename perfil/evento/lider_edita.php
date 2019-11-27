<?php
$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_POST['idLider'])){
    $idLider = $_POST['idLider'];
}


if(isset($_POST['cadastrar'])){
    $nome = addslashes($_POST['nome']);
    $nomeArtistico = $_POST['nomeArtistico'];
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $drt = $_POST['drt'];
}



if(isset($_POST['cadastrar'])){
    $mensagem ="";
    $sqlInsert = "INSERT INTO pessoa_fisicas (nome, nome_artistico, email) VALUES ('$nome','$nomeArtistico','$email')"; // esta inserindo no banco

    echo $sqlInsert;
    if(mysqli_query($con,$sqlInsert)) {
        $idLider = recuperaUltimo("pessoa_fisicas");
        //cadastra o telefone
        foreach ($telefones AS $telefone) {
            if (!empty($telefone)) {
                $sqlTelefone = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone, publicado) VALUES ('$idLider','$telefone',1)";
                mysqli_query($con, $sqlTelefone);
                echo $sqlTelefone;
            }
        }
        if ($drt != NULL) {
            $sqlDRT = "INSERT INTO drts (pessoa_fisica_id, drt, publicado)  VALUES ('$idLider','$drt',1)";
            if (!mysqli_query($con, $sqlDRT)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlDRT;
            }
        }
        $mensagem .= mensagem("success", "Cadastrado com sucesso!");
    } else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}


$lider = recuperaDados("pessoa_fisicas", "id",$idLider);
$drt = recuperaDados("drts", "pessoa_fisica_id", $idLider);
echo $drt;

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
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Líder do Evento</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($resultado)){echo $resultado;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=lider_edita" role="form">
                        <div class="box-body">
                            <input type='hidden' name='idLider' value="">

                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value="<? $lider['nome'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nome">Nome Artístico: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value="<? $lider['nome_artistico'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type='email' class='form-control' id='email' name='email' maxlength='60' placeholder='Digite o e-mail' value=<? $lider['email'] ?> required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #1</label>
                                    <input type="text" class="form-control"
                                           id='telefone' name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" class="form-control"
                                           id='telefone1' name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                           value="<?= $arrayTelefones[1]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #3</label>
                                    <input type="text" class="form-control"
                                           id='telefone2' name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[2]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="drt">DRT: <i>(Somente para artes cênicas)</i></label>
                                    <input type="text" class="form-control" id='drt' name='drt' onkeyup="mascara( this, mtel );"
                                           maxlength="15"value=<? $drt['drts'] ?>>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista"><button type="button" class="btn btn-default">Voltar</button></a>
                            <button type="submit" name="edita" class="btn btn-info pull-right">Cadastrar</button>
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