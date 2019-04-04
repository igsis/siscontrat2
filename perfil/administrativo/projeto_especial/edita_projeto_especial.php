<?php
$con = bancoMysqli();

if(isset($_POST['cadastra']) || (isset($_POST['edita']))){
    $nome = addslashes($_POST['nome']);

    if(isset($_POST['cadastra'])){
        $sql = "INSERT INTO projeto_especiais (projeto_especial)
                VALUES ('$nome')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Projeto Especial cadastrado com sucesso!");
            $idProjetoEspecial = recuperaUltimo('projeto_especiais');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de projeto especial! Tente novamente.");
        }
    }

    if(isset($_POST['edita'])){
        $idProjetoEspecial = $_POST['idProjetoEspecial'];

        $sql = "UPDATE projeto_especiais SET projeto_especial = '$nome' WHERE id = '$idProjetoEspecial'";

        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Projeto Especial editado com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao salvar o projeto especial! Tente novamente.");
        }
    }
}

if(isset($_POST['carregar'])){
    $idProjetoEspecial = $_POST['idProjetoEspecial'];
}

$projetoEspecial = recuperaDados('projeto_especiais', 'id', $idProjetoEspecial);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Projeto Especial</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Projeto Especial</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=projeto_especial&sp=edita_projeto_especial"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nome">Projeto especial nome *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required value="<?= $projetoEspecial['projeto_especial'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=projeto_especial&sp=projeto_especial_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idProjetoEspecial" id="idProjetoEspecial" value="<?= $idProjetoEspecial ?>">
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