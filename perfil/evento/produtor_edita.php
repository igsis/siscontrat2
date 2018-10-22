<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

//if(isset($_POST['idAtracao'])){
//    $idAtracao = $_POST['idAtracao'];
//}


if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $idAtracoes = $_POST['idAtracao'];
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
        $idProdutor = recuperaUltimo("produtores");
        $sqlUpdate = "UPDATE `atracoes`
                      SET produtor_id = '$idProdutor' 
                      WHERE id ='$idAtracoes'";
        if(mysqli_query($con,$sqlUpdate)){
            mensagem("sucess","Produtor cadastrado");
            $idAtracao = recuperaUltimo("atracoes");
        }
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

                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" maxlength="120">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60" placeholder="Digite o e-mail">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #1</label>
                                    <input type="text" class="form-control" id="telefone1" name="telefone1" maxlength="15" placeholder="Digite o Telefone principal">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" class="form-control" id="telefone2" name="telefone2" maxlength="15" placeholder="Digite o Telefone secundário">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea name="observacao" id="observacao" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
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
