<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $nomeEvento = $_POST['nomeEvento'];
    $relacao_juridica_id = $_POST['relacaoJuridica'];
    $projeto_especial_id = $_POST['projetoEspecial'];
    $sinopse = $_POST['sinopse'];
    $tipo = $_POST['tipo'];
    $fiscal_id = $_POST['fiscal'];
    $suplente_id = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $original = $_POST['originall'];
    $contratacao = $_POST['contratacao'];
    $eventoStatus = "1";
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO eventos (nome_evento,
                                 relacao_juridica_id, 
                                 projeto_especial_id, 
                                 tipo, 
                                 sinopse, 
                                 fiscal_id, 
                                 suplente_id, 
                                 usuario_id, 
                                 contratacao, 
                                 original, 
                                 evento_status_id) 
                          VALUES ('$nomeEvento',
                                  '$relacao_juridica_id',
                                  '$projeto_especial_id',
                                  '$tipo',
                                  '$sinopse',
                                  '$fiscal_id',
                                  '$suplente_id',
                                  '$usuario',
                                  '$contratacao',
                                  '$original',
                                  '$eventoStatus')";

    if(mysqli_query($con, $sql))
    {
        $idEvento = recuperaUltimo("eventos");
        $mensagem = mensagem("success","Cadastrado com suscesso");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idEvento = $_POST['idEvento'];
    $sql = "UPDATE eventos SET nome_evento='$nomeEvento', relacao_juridica_id = '$relacao_juridica_id', projeto_especial_id = '$projeto_especial_id', tipo = '$tipo', sinopse = '$sinopse', fiscal_id = '$fiscal_id', suplente_id = '$suplente_id', contratacao = '$contratacao', original = '$original' WHERE id = '$idEvento'";
    If(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Cadastrado com suscesso");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente");
        //gravarLog($sql);
    }
}

$evento = recuperaDados("eventos","id",$idEvento);
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
                        <h3 class="box-title">Informações Gerais</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>

                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nomeEvento">Nome do evento</label>
                                <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                       placeholder="Digite o nome do evento" maxlength="240" value="<?= $evento['nome_evento']?>">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="relacaoJuridica">Tipo de relação jurídica</label>
                                    <select class="form-control" name="relacaoJuridica" id="relacaoJuridica">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("relacao_juridicas", $evento['relacao_juridica_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="projetoEspecial">Projeto Especial</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", $evento['projeto_especial_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sinopse">Sinopse</label>
                                <textarea name="sinopse" id="sinopse" class="form-control" rows="5"><?= $evento['sinopse'] ?></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-4">
                                    <label for="tipo">Tipo do Evento</label>
                                    <select class="form-control" id="tipo" name="tipo">
                                        <option value="">Selecione uma opção...</option>
                                        <option value="1">Atração</option>
                                        <option value="2">Oficina</option>
                                        <option value="3">Filme</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fiscal">Fiscal</label>
                                    <select class="form-control" id="fiscal" name="fiscal">
                                        <option value="">Selecione um fiscal...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['fiscal_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="suplente">Suplente</label>
                                    <select class="form-control" id="suplente" name="suplente">
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['suplente_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label for="original">É um evento original?</label> <br>
                                    <label><input type="radio" name="original" value="1" <?= $evento['original'] == 1 ? checked : NULL ?>> Sim </label>
                                    <label><input type="radio" name="original" value="0" <?= $evento['original'] == 0 ? checked : NULL ?>> Não </label>
                                </div>

                                <div class="form-group">
                                    <label for="original">É contratado?</label> <br>
                                    <label><input type="radio" name="contratacao" value="1" checked> Sim </label>
                                    <label><input type="radio" name="contratacao" value="0"> Não </label>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-info pull-right">Cadastrar</button>
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