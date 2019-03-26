<?php
$con = bancoMysqli();

if(isset($_POST['cadastra']) || (isset($_POST['edita']))){
    $nome = $_POST['nome'];
    $token = $_POST['token'];

    if(isset($_POST['cadastra'])){
        $sql = "INSERT INTO perfis (descricao, token)
                VALUES ('$nome', '$token')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Perfil cadastrado com sucesso!");
            $idPerfil = recuperaUltimo('perfis');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de perfil! Tente novamente.");
        }
    }

    if(isset($_POST['edita'])){
        $idPerfil = $_POST['idPerfil'];

        $sql = "UPDATE perfis SET descricao = '$nome', token = '$token' WHERE id = '$idPerfil'";

        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Perfil editado com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao salvar o perfil! Tente novamente.");
        }
    }
}

if(isset($_POST['carregar'])){
    $idPerfil = $_POST['idPerfil'];
}

$perfil = recuperaDados('perfis', 'id', $idPerfil);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Perfil</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Perfil</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=perfil&sp=edita_perfil"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-9">
                                    <label for="nome">Descrição do perfil *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required value="<?= $perfil['descricao'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="token">Token *</label>
                                    <input type="text" id="token" name="token" class="form-control" required value="<?= $perfil['token'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=perfil&sp=perfil_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idPerfil" id="idPerfil" value="<?= $idPerfil ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                Salvar
                            </button>
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