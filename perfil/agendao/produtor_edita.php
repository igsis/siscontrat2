<?php
    $con = bancoMysqli();
    include "includes/menu_interno.php";

    //if(isset($_POST['idAtracao'])){
    //    $idAtracao = $_POST['idAtracao'];
    //}

    if (isset($_POST['cadastra']) || isset($_POST['edita'])){
        $nome = addslashes($_POST['nome']);
        $email = $_POST['email'];
        $telefone1 = $_POST['telefone1'];
        $telefone2 = $_POST['telefone2'];
    }
    if (isset($_POST['cadastra'])){
        $idAtracoes = $_POST['idAtracoes'];
        $sqlInsert = "INSERT INTO `produtores`
                      (nome, email, telefone1, telefone2)
                      VALUES ('$nome','$email','$telefone1','$telefone2')";

        if (mysqli_query($con,$sqlInsert)){
            $idProdutor = recuperaUltimo("produtores");
            $sqlUpdate = "UPDATE `atracoes`
                          SET produtor_id = '$idProdutor'
                          WHERE id ='$idAtracoes'";
            if(mysqli_query($con,$sqlUpdate)){
                $resultado = mensagem("success","Produtor cadastrado");
                $idAtracao = recuperaUltimo("atracoes");
            }else{
                $resultado = mensagem("danger","Erro ao cadastrar");
            }
        }
    }

    

    if (isset($_POST['edita'])){
        $idProdutor = $_POST['idProdutor'];
        $sql  = "UPDATE `produtores`
                 SET  nome = '$nome',
                      email = '$email',
                      telefone1 = '$telefone1',
                      telefone2 = '$telefone2',
                 WHERE id = '$idProdutor'";
        if (mysqli_query($con,$sql)){
            $resultado = mensagem("success","Cadastro atualizado com sucesso");

        }
        else{
            $resultado = mensagem("danger","Erro ao atualizar");
        }
    }

    if(isset($_POST['carregar'])){
        $idProdutor = $_POST['idProdutor'];
    }

    $row = recuperaDados("produtores","id", $idProdutor);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <h2 class="page-header">Produtor do Evento</h2>
        <!-- START FORM-->
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="row" align="center">
                        <?php if(isset($resultado)){echo $resultado;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=agendao&p=produtor_edita" role="form">
                        <div class="box-body">
                            <input type='hidden' name='idProdutor' value="<?= $idProdutor ?>">
                            
                            <div class="form-group">
                                <label for="nome">Nome do produtor de evento *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value='<?= $row['nome']?>' required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="telefone1">Telefone #1</label>
                                    <input type="text" class="form-control" id='telefone' name='telefone1' maxlength='15' onkeyup="mascara( this, mtel );" placeholder='Digite o Telefone principal' required value='<?= $row['telefone1']?>'>
                                    
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" class="form-control" id='telefone' name='telefone2' onkeyup="mascara( this, mtel );" maxlength="15" placeholder='Digite o Telefone secundário' value='<?= $row['telefone2']?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="email">E-mail</label>
                                    <input type='email' class='form-control' id='email' name='email' maxlength='60' placeholder='Digite o e-mail' value='<?= $row['email']?>' required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="edita" class="btn btn-theme btn-block" >ALTERAR</button>
                        <div class="box-footer">
                            <a href="?perfil=agendao&p=atracoes_lista"><button type="button" class="btn btn-default">Voltar</button></a>
                            <a href="?perfil=agendao&p=ocorrencia_cadastro"><button type="button" class="btn btn-info pull-right">Avançar</button></a>
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
