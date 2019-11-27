<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
if(isset($_POST['idLider'])){
    $idLider = $_POST['idLider'];
}
if(isset($_POST['cadastra'])){
    $nome = addslashes($_POST['nome']);
    $nomeArtistico = $_post['nomeArtistico'];
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $drt = $_POST['drt'];
}
if(isset($_POST['cadastra'])){
    $mensagem ="";
    $sqlInsert = "INSERT INTO pessoa_fisica` (nome,nome_artistico,email) VALUES('$nome','$nomeArtistico','$email')";
    if(mysqli_query($con,$sqlInsert)) {
        $idLider = recuperaUltimo("pessoa_fisicas");
        //cadastra o telefone
        foreach ($telefones as $telefone) {
            if (!empty($telefone)) {
                $sqlTelelefone = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone, publicado) VALUES ('$idLider','$telefone',1)";
                mysqli_query($con, $sqlTelefone);
            }
        }
        if ($drt != NULL) {
            $sqlDRT = "INSERT INTO siscontrat.`drts` (pessoa_fisica_id, drt, publicado)  VALUES ('$idLider','$drt',1)";
            if (!mysqli_query($con, $sqlDRT)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlDRT;
            }
        }

    }
}
?>
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
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value='' required>
                            </div>
                            <div class="form-group">
                                <label for="nome">Nome Artístico: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value='' required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type='email' class='form-control' id='email' name='email' maxlength='60' placeholder='Digite o e-mail' value='' required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #1</label>
                                    <input type="text" class="form-control" id='telefone1' name='telefone1' maxlength='15' onkeyup="mascara( this, mtel );" placeholder='Digite o Telefone principal' required value=''>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" class="form-control" id='telefone2' name='telefone2' onkeyup="mascara( this, mtel );" maxlength="15" placeholder='Digite o Telefone secundário' value=''>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #3</label>
                                    <input type="text" class="form-control" id='telefone3' name='telefone3' maxlength='15' onkeyup="mascara( this, mtel );" placeholder='Digite o terceiro Telefone' required value=''>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="drt">DRT: <i>(Somente para artes cênicas)</i></label>
                                    <input type="text" class="form-control" id='drt' name='drt' onkeyup="mascara( this, mtel );" maxlength="15"value=''>
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