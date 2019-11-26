<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

if (isset($_POST['idPf']) || isset($_POST['idProponente'])) {
    $idPf = $_POST['idPf'] ?? $_POST['idProponente'];
}

$pf = recuperaDados()
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">ARTISTA - Líder do Grupo ou Artista Solo</h2>
        <h4>No caso de espetáculos de teatro, dança e circo, este deve ser do elenco ou o diretor do espetáculo e deve ter DRT. No caso espetáculo de música, este deve ser um músico do espetáculo</h4>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header">
                        <button type="submit" name="edita" class="btn btn-info pull-left">TROCAR O ARTISTA</button>
                    </div>
                    <div class="row" align="center">
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=pf_pesquisa" role="form">
                        <div class="box-body">
                            <input type='hidden' name='idProdutor' value="">
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120' value='' placeholder='Digite o nome'required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Nome Artístico:</label>
                                    <input type='text' class='form-control' id='email' name='email' maxlength='15'  value='' required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <input type='email' class='form-control' id='email' name='email' maxlength='15' placeholder='Digite o e-mail' value='' required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Celula*:</label>
                                    <input type="text" class="form-control" id='celular' name='celular' maxlength='15' onkeyup="mascara( this, mtel );" placeholder='Digite seu celular' required value=''>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">Telefone #2</label>
                                    <input type="text" class="form-control" id='telefone' name='telefone2' onkeyup="mascara( this, mtel );" maxlength="15" placeholder='Digite o Telefone secundário' value=''>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone1">Telefone #3</label>
                                    <input type="text" class="form-control" id='telefone' name='telefone3' maxlength='15' onkeyup="mascara( this, mtel );" placeholder='Digite o terceiro Telefone'>

                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefone2">DRT: <i>(Somente para artes cênicas)</i></label>
                                    <input type="text" class="form-control" id='telefone' name='telefone2' onkeyup="mascara( this, mtel );" maxlength="15"value=''>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=pf_pesquisa><button type="button" class="btn btn-default">Voltar</button></a>
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
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
