<?php
include "includes/menu_principal.php";
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';

$con = bancoMysqli();

if (isset($_POST['cadastraEspaco'])) {
    $idLocal = $_POST['local'];
    $espaco = $_POST['espaco'];

    $existe = 0;
    $sqlEspacos = "SELECT * FROM espacos WHERE local_id = '$idLocal'";
    $queryEspacos = mysqli_query($con, $sqlEspacos);
    while ($espacos = mysqli_fetch_array($queryEspacos)) {
        if ($espacos['espaco'] == $espaco) {
            $existe = 1;
        }
    }

    if ($existe != 0) {

    } else {

        $sql = "INSERT INTO espacos (local_id ,espaco, publicado)
                VALUES ('$idLocal', '$espaco', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);

            $mensagem = mensagem("success", "Adição de espaço efetuado com sucesso");
            $fechar = "<script defer='defer'>
                        window.onload = function() {
                            setTimeout(window.close ,8000);  
                        }
                    </script>";

            echo $fechar;
        } else {
            $mensagem = mensagem("danger", "Erro na adição de espaço! Tente novamente.");
        }
    }

}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Adição de Espaço</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Espaços</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=adicionar_espaco"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="instituicao">Instituição: *</label>
                                    <select name="instituicao" id="instituicao" class="form-control" required readonly>
                                        <option value="6">Outros</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="local">Local: </label>
                                    <select name="local" id="local" class="form-control" required>
                                       <!-- -
/*                                            geraOpcaoPublicado('locais');
                                        */?> -->
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="sigla">Espaço: </label>
                                    <input type="text" class="form-control" id="espaco" name="espaco" required>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name="cadastraEspaco" id="cadastraEspaco" class="btn btn-primary pull-right">
                                Cadastrar
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

<script type="text/javascript">

    const url = `<?=$url?>`;

    getLocais(6, 1);

    function getLocais(idInstituicao, selectedId) {
        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    if (selectedId == local.id) {
                        $('#local').append(`<option value='${local.id}' selected>${local.local}</option>`).focus();
                    } else {
                        $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                    }
                }
            })
    }
</script>


