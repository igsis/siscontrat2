<?php
$con = bancoMysqli();
$conn = bancoCapac();
$year = date("Y");

if (isset($_POST['trocar'])) {
    $resposta = $_POST['resposta'];

    if ($resposta == '1')
        $liberar = 'LIBERADO';
    else
        $liberar = 'BLOQUEADO';

    $sql = "UPDATE formacao_cadastro SET situacao = '$resposta', descricao = '$liberar' WHERE ano = '$year'";

    if (mysqli_query($conn, $sql)) {
        gravarLog($sql);
        $mensagem = mensagem("success", "Status alterado para: " . $liberar . " com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Não foi possivel alterar o status para: " . $liberar . ". Tente novamente!");
    }
}

$status = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM formacao_cadastro WHERE ano = '$year' LIMIT 0,1"));
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Trocar Status</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=capacstatus&spp=index"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h2 class="box-title">Status atual: <?= $status['descricao'] ?></h2>
                                    <input type="hidden" id="resposta" name="resposta" class="form-control"
                                           value="<?= $status['situacao'] == 1 ? 0 : 1 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="trocar" id="trocar" class="btn btn-primary pull-right">
                                Alterar Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
