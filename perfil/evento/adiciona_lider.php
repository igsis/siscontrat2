<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

if (isset($_POST['adicionar'])){
    $documento = $_POST['documentacao'];
    $tipoDocumento = $_POST['tipoDumentacao'];
}

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
                        <button type="submit" name="trocaArtista" class="btn btn-info pull-left">TROCAR O ARTISTA</button>
                    </div>
                    <div class="row" align="center">
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=lider_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120'
                                       placeholder='Digite o nome'required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Nome Artístico:</label>
                                    <input type='text' class='form-control' id='nomeArtistico'
                                           name='nomeArtistico' maxlength='120' required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <input type='email' class='form-control' id='email' name='email'
                                           maxlength='120' placeholder='Digite o e-mail' required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="email">Telefone: 1#</label>
                                    <input type="text" id="telefone" name="telefone[0]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" required maxlength="11">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="email">Telefone: 2#</label>
                                    <input type="text" id="telefone" name="telefone[1]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" required maxlength="11">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="email">Telefone: 3#</label>
                                    <input type="text" id="telefone" name="telefone[2]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" required maxlength="11">

                                </div>
                                <div class="form-group col-md-3">
                                    <label for="drt">DRT: <i>(Somente para artes cênicas)</i></label>
                                    <input type="text" name="drt" class="form-control" maxlength="15"
                                           placeholder="Digite o DRT">
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=pesquisa_lider><button type="button" class="btn btn-default">Voltar</button></a>
                            <button type="submit" name="cadastrar" class="btn btn-info pull-right">Cadastrar</button>
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
