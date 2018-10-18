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
    $original = $_POST['original'];
    $contratacao = $_POST['contratacao'];
    $eventoStatus = "1";
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO eventos (nome_evento,
                                 relacao_juridica_id,
                                 projeto_especial_id,
                                 tipo_evento_id,
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
        $_SESSION['idEvento'] = $idEvento;
        $mensagem = mensagem("success","Cadastrado com suscesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idEvento = $_POST['idEvento'];
    $sql = "UPDATE eventos SET nome_evento='$nomeEvento', relacao_juridica_id = '$relacao_juridica_id', projeto_especial_id = '$projeto_especial_id', tipo_evento_id = '$tipo', sinopse = '$sinopse', fiscal_id = '$fiscal_id', suplente_id = '$suplente_id', contratacao = '$contratacao', original = '$original' WHERE id = '$idEvento'";
    If(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Gravado com suscesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if(isset($_POST['carregar'])){
    $idEvento = $_POST['idEvento'];
    $_SESSION['idEvento'] = $idEvento;
}

$evento = recuperaDados("eventos","id",$idEvento);
?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cinema</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Filme</th>
                                <th>Ano </th>
                                <th>Duração</th>
                                <th>Diretor</th>
                                <th width="10%"></th>
                                <th width="10%"></th>
                            </tr>
                            </thead>

                            <!-- <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)){
                                echo "<tr>";
                                echo "<td>".$evento['nome_evento']."</td>";
                                echo "<td>".$evento['tipo_evento']."</td>";
                                echo "<td>".$evento['status']."</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='".$evento['idEvento']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\">Carregar</button>
                                    </form>
                                </td>";
                                echo "<td>
                    <button type=\"button\" class=\"btn btn-block btn-danger\">Apagar</button>
                  </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?> -->
                            <tfoot>
                            <tr>
                              <th>Filme</th>
                              <th>Ano </th>
                              <th>Duração</th>
                              <th>Diretor</th>
                              <th width="10%"></th>
                              <th width="10%"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
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
