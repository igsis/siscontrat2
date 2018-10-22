<?php
    $con = bancoMysqli();
    include "includes/menu_interno.php";

    //if(isset($_POST['idAtracao'])){
    //    $idAtracao = $_POST['idAtracao'];
    //}

    if (isset($_POST['cadastra']) || isset($_POST['edita'])){
        $idAtracoes = $_POST['idAtracoes'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone1 = $_POST['telefone1'];
        $telefone2 = $_POST['telefone2'];
        $observacao = $_POST['observacao'];
    }
    if (isset($_POST['cadastra'])){
        $sqlInsert = "INSERT INTO `produtores`
                      (nome, email, telefone1, telefone2, observacao)
                      VALUES ('$nome','$email','$telefone1','$telefone2','$observacao')";

        if (mysqli_query($con,$sqlInsert)){
            $_SESSION["produtor"] = recuperaUltimo("produtores");
            $sqlUpdate = "UPDATE `atracoes`
                          SET produtor_id = '".$produtor."'
                          WHERE id ='$idAtracoes'";
            if(mysqli_query($con,$sqlUpdate)){
                $resultado = mensagem("sucess","Produtor cadastrado");
                $idAtracao = recuperaUltimo("atracoes");
            }else{
                mensagem("danger","Erro atrações");
            }
        }
    }

    $row = recuperaDados("produtores","id", $_SESSION["produtor"]);

    if (isset($_POST['edita'])){
        $sql  = "UPDATE `produtores`
                 SET  nome = '$nome',
                      email = '$email',
                      telefone1 = '$telefone1',
                      telefone2 = '$telefone2',
                      observacao = '$observacao'
                 WHERE id = ".$row['id'];
        if (mysqli_query($con,$sql)){
            $resultado = mensagem("sucess","Cadastro atualizado com sucesso");

        }
        else{
            $resultado = mensagem("danger","Erro ao atualizar");
        }

    }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtor do Evento</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=produtor_edita" role="form">
                        <div class="box-body">
                            <?php
                            echo "<input type='hidden' name='idAtracoes' value='".$idAtracoes."''>";
                            ?>
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <?php
                                    echo "<input type='text' class='form-control' id='nome' name='nome' maxlength='120' value='".$row['nome']."' required>";
                                ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <?php
                                        echo "<input type='email' class='form-control' id='email' name='email' maxlength='60' placeholder='Digite o e-mail' value='".$row['email']."' required>";
                                    ?>

                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #1</label>
                                    <?php
                                        echo "<input type='text' class='form-control' id='telefone1' name='telefone1' maxlength='15' placeholder='Digite o Telefone principal' required value='".$row['telefone1']."'>";
                                    ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <?php
                                        echo "<input type='text' class='form-control' id='telefone2' name='telefone2' maxlength='15' placeholder='Digite o Telefone secundário' value='".$row['telefone2']."'>";
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <?php
                                    echo "<textarea name='observacao' id='observacao' class='form-control' rows='5'>".$row['observacao']."</textarea>";
                                ?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <button type="submit" name="edita" class="btn btn-info pull-right">Alterar</button>
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
