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
        <h2 class="page-header">Produtor do Evento</h2>
        <!-- START FORM-->
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=agendao&p=produtor_edita" role="form">
                        <div class="box-body">
                            <input type='hidden' name='idAtracoes' value='<?= $idAtracao?>'>

                            <div class="form-group">
                                <label for="nome">Nome do produtor de evento *</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" maxlength="120" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="telefone1">Telefone #1 *</label>
                                    <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone" name="telefone1" maxlength="15" onkeyup="mascara( this, mtel );" placeholder="Digite o Telefone principal" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone" name="telefone2" maxlength="15" onkeyup="mascara( this, mtel );" placeholder="Digite o Telefone secundário">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="email">E-mail *</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60" placeholder="Digite o e-mail" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="cadastra" class="btn btn-theme btn-block" >GRAVAR</button>
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
