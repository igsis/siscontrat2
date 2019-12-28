<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

if (isset($_POST['adicionar']) || $_POST['adicionaLider'] || $_POST['adicionarLider']) {
    $documento = $_POST['documentacao'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $idPedido = $_POST['idPedido'];
    $idAtracao = $_POST['idAtracao'];
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">ARTISTA - Líder do Grupo ou Artista Solo</h2>
        <h4>No caso de espetáculos de teatro, dança e circo, este deve ser do elenco ou o diretor do espetáculo e deve
            ter DRT. No caso espetáculo de música, este deve ser um músico do espetáculo</h4>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="row" align="center">
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=lider_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input type='text' class='form-control' id='nome' name='nome' maxlength='120'
                                       pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,120}" title="Apenas letras"
                                       placeholder='Digite o nome' required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nomeArtistico">Nome Artístico: </label>
                                    <input type='text' class='form-control' id='nomeArtistico'
                                           name='nomeArtistico' maxlength='120'>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type='text' class='form-control' id='email' name='email'
                                           maxlength='120' placeholder='Digite o e-mail' required>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if ($tipoDocumento == 1) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="cpf">CPF: *</label>
                                        <input type='text' class='form-control' id='cpf' name='cpf'
                                               maxlength="15" readonly value="<?= $documento ?>">
                                    </div>
                                <?php } else if ($tipoDocumento == 2) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="passaporte">Passaporte</label>
                                        <input type='text' class='form-control' id='passaporte' name='passaporte'
                                               maxlength="8" readonly value="<?= $documento ?>">
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-6">
                                    <label for="drt">DRT: <i>(Somente para artes cênicas)</i></label>
                                    <input type="text" name="drt" class="form-control" maxlength="15"
                                           placeholder="Digite o DRT">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: 1#*</label>
                                    <input type="text" id="telefone" name="telefone[0]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" required maxlength="15">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: 2#</label>
                                    <input type="text" id="telefone" name="telefone[1]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" maxlength="15">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: 3#</label>
                                    <input type="text" id="telefone" name="telefone[2]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" maxlength="15">

                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="tipoDocumento" value="<?= $tipoDocumento ?>">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
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
