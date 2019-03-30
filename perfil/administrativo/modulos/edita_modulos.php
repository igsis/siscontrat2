<?php
$con = bancoMysqli();

if(isset($_POST['carregar'])){
    $idModulo = $_POST['idModulo'];

    $modulos = recuperaDados("modulos", "id", $idModulo);
}

if(isset($_POST['cadastra']) || (isset($_POST['edita']))){
    $sigla = $_POST['sigla'];
    $desc = $_POST['descricao'];
    $cor_id = $_POST['cor'];

    if(isset($_POST['cadastra'])){
        $sql = "INSERT INTO modulos (sigla, descricao, cor_id)
                VALUES ('$sigla', '$desc', '$cor_id')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Módulo cadastrado com sucesso!");
            $idModulo = recuperaUltimo('modulos');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro do módulo! Tente novamente.");
        }
    }

    if(isset($_POST['edita'])){
        $idModulo = $_POST['idModulo'];

        $sql = "UPDATE modulos SET sigla = '$sigla', descricao = '$desc', cor_id = '$cor_id' WHERE id = '$idModulo'";

        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Módulo editado com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao editar o módulo! Tente novamente.");
        }
    }
}
$modulos = recuperaDados('modulos', 'id', $idModulo);

$cores = recuperaDados("cores", "id", $modulos['cor_id']);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Módulo</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Módulos</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=modulos&sp=edita_modulos"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="sigla">Sigla: </label>
                                    <input type="text" class="form-control" id="sigla" name="sigla" value="<?=$modulos['sigla']?>"
                                           maxlength="70" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="descricao">Descrição: </label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" required maxlength="12" value="<?=$modulos['descricao']?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="cor">Cor: </label>
                                    <select class="form-control" id="cor" name="cor">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("cores", $cores['id']);
                                            $cor = explode("-", $cores['text-color']);
                                            
                                            if (count($cor) > 2) {
                                                $cor = $cor[1] . "-" . $cor[2];
                                            } else {
                                                $cor = $cor[1];
                                            }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-1 cor text-center" style="margin-top: 6px;">
                                    <label for='cor'><span class='glyphicon glyphicon-eye-open'></span></label>
                                    <input type='text' class='form-control bg-<?=$cor?>' disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=modulos&sp=modulos_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>">
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

<script>
    $("#cor").on("change", function () {

        let selecionado = $("#cor :selected").text();

        let cor = selecionado.split("-");

        if (cor.length > 2) {
            cor = cor[1] + "-" + cor[2];
        } else {
            cor = cor[1];
        }

        console.log(cor);

        $(".cor").html("<label for='cor'><span class='glyphicon glyphicon-eye-open'></span></label><input type='text' class='form-control bg-"+ cor + "' disabled>");

    });
</script>