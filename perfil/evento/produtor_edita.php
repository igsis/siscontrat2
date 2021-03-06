<?php
    $con = bancoMysqli();
    include "includes/menu_interno.php";

    if (isset($_POST['edita'])){
        $nome = trim(addslashes($_POST['nome']));
        $email = trim($_POST['email']);
        $telefone1 = $_POST['telefone1'];
        $telefone2 = $_POST['telefone2'] ?? NULL;
        $observacao = trim(addslashes($_POST['observacao'])) ?? NULL;
        $idProdutor = $_POST['idProdutor'];
        $sql  = "UPDATE `produtores`
                 SET  nome = '$nome',
                      email = '$email',
                      telefone1 = '$telefone1',
                      telefone2 = '$telefone2',
                      observacao = '$observacao'
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

        <!-- START FORM-->
        <h2 class="page-header">Edição de Produtor</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtor do Evento</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($resultado)){echo $resultado;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=produtor_edita" role="form">
                        <div class="box-body">
                            <input type='hidden' name='idProdutor' value="<?= $idProdutor ?>">
                            
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value='<?= $row['nome']?>' required pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,120}" title="Apenas letras">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type='email' class='form-control' id='email' name='email' maxlength='60' placeholder='Digite o e-mail' value='<?= $row['email']?>' required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #1</label>
                                    <input type="text" class="form-control" id='telefone' name='telefone1' maxlength='15' onkeyup="mascara( this, mtel );" placeholder='Digite o Telefone principal' required value='<?= $row['telefone1']?>' pattern=".{14,15}"  title="14 a 15 caracteres">
                                    
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" class="form-control" id='telefone' name='telefone2' onkeyup="mascara( this, mtel );" maxlength="15" placeholder='Digite o Telefone secundário' value='<?= $row['telefone2']?>' pattern=".{14,15}"  title="14 a 15 caracteres">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea name='observacao' id='observacao' class='form-control' rows='3'><?=$row['observacao'] ?></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista"><button type="button" class="btn btn-default">Voltar</button></a>
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
