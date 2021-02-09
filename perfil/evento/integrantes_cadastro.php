<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

$atracao_id = $_POST['atracao_id'];

$method = $_POST['_method'];

if (!isset($_POST['integrante_id'])) {
    $cpf_passaporte = $_POST['documento'];
} else {
    $id = $_POST['integrante_id'];
    $integrante = $con->query("SELECT * FROM integrantes WHERE id = '$id'")->fetch_assoc();
    $cpf_passaporte = $integrante['cpf_passaporte'];
}
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Integrante</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Cadastro de Integrante</h3>
                    </div>
                    <!-- /.box-header -->
                    <form action="?perfil=evento&p=integrantes_lista" method="post">
                        <input type="hidden" name="<?= $method ?>">
                        <input type="hidden" name="idAtracao" value="<?= $atracao_id ?>">
                        <?php if (isset($id)): ?>
                            <input type="hidden" name="integrante_id" value="<?= $id ?>">
                        <?php endif ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="cpf">CPF / Passaporte: *</label>
                                    <input type="text" id="cpf" name="cpf_passaporte" value="<?= $cpf_passaporte ?>"
                                           class="form-control" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" id="nome"
                                           placeholder="Digite o nome" maxlength="120" required
                                           pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,120}"
                                           title="Apenas letras" value="<?= $integrante['nome'] ?? ''?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="rg">RG: *</label>
                                    <input type="text" class="form-control" name="rg" id="rg"
                                           placeholder="Digite o RG" maxlength="20" value="<?= $integrante['rg'] ?? ''?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="funcao">Função: *</label>
                                <input type="text" name="funcao" class="form-control" id="funcao">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success pull-right">Adicionar</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </section>
    <!-- /.content -->
</div>