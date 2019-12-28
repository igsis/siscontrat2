<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

if(isset($_POST['idAtracao'])){
    $idAtracao = $_POST['idAtracao'];

}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Produtor</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtor do Evento</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=atracoes_lista" role="form">
                        <div class="box-body">
                            <input type='hidden' name='idAtracoes' value='<?= $idAtracao?>'>

                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" maxlength="120" required pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,120}" title="Apenas letras">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60" placeholder="Digite o e-mail" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #1: *</label>
                                    <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone" name="telefone1" maxlength="15" onkeyup="mascara( this, mtel );" placeholder="Digite o Telefone principal" required pattern=".{14,15}"  title="14 a 15 caracteres">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone" name="telefone2" maxlength="15" onkeyup="mascara( this, mtel );" placeholder="Digite o Telefone secundário" pattern=".{14,15}"  title="14 a 15 caracteres">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea name="observacao" id="observacao" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista"><button type="button" class="btn btn-default">Voltar</button></a>
                            <button type="submit" name="cadastraProdutor" class="btn btn-info pull-right">Cadastrar</button>
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
