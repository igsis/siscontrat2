<?php

include "includes/menu_interno.php";
$con = bancoMysqli();
$idPedido = $_SESSION['idPedido'];

if(isset($_POST['tipoRepresentante']) && isset($_POST['idPj'])) {
    $_SESSION['tipoRepresentante'] = $_POST['tipoRepresentante'];
    $_SESSION['idPj'] = $_POST['idPj'];
    $idPj = $_SESSION['idPj'];
}

if (isset($_POST['pesquisa'])) {

    $cpf = $_POST['cpf'];
    $sql = "SELECT * FROM siscontrat.representante_legais WHERE cpf ='$cpf' LIMIT 1";
    $query = mysqli_query($con,$sql);

    if(mysqli_num_rows($query) > 0) {
        $resultado = mysqli_fetch_array($query);
        $mensagem = "<form method='post' action='?perfil=evento&p=representante_edita'>
                        <tr>
                            <td>".$resultado['nome']."</td>
                            <td>".$resultado['cpf']."</td>
                            <td>".$resultado['rg']."</td>
                            <td>
                                <input type='hidden' name='idRepresentante' value='".$resultado['id']."'>
                                <button type='submit' class='btn btn-primary' name='carregar'>Selecionar</button>
                            </td>
                        </tr>
                    </form>";

    }else{
        $mensagem = "<form action='?perfil=evento&p=representante_cadastro' method='post'>
                        <tr>
                            <td>Representante não cadastrado</td>
                            <td>
                                <input type='hidden' name='documentacao' value='".$cpf."'>
                                <button type='submit' class='btn btn-primary' name='adicionar'><i class='glyphicon glyphicon-plus'></i> Adicionar</button>
                            </td>
                        </tr>
                     </form>";
    }
}

?>
<script>
    $(document).ready(function () {
        $('#cpf').mask('000.000.000-00', {reverse: true});
    });
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Busca de representante</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Procurar pessoa fisica</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=representante_busca" method="post">
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength=14 name="cpf" value="<?= empty($cpf)?'':$cpf ?>" id="cpf" data-mask="000.000.000-00" placeholder="Digite o CPF aqui. . . . ">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" name="pesquisa" type="submit"><i class="glyphicon glyphicon-search"></i> Procurar</button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <div class="panel panel-default">
                            <!-- Default panel contents -->
                            <!-- Table -->
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <tr></tr>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($mensagem)){
                                        echo $mensagem;
                                    } ?>
                                </tbody>
                            </table>
                        </div>


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