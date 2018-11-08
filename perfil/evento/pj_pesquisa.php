<?php

$con = bancoMysqli();
// include "includes/menu_interno.php";
unset($_SESSION['idPj_pedido']);

include "includes/menu_pj.php";

$exibir = ' ';
$resultado = "<td></td>";
$procurar = NULL;



if (isset($_POST['procurar'])){

    $procurar = $_POST['procurar'];

    if ($procurar != NULL) {

        $queryCNPJ = "SELECT  id, razao_social, cnpj, email
                        FROM siscontrat.`pessoa_juridicas`
                        WHERE cnpj = '$procurar'";

            if ($result = mysqli_query($con,$queryCNPJ)) {

                $resultCNPJ = mysqli_num_rows($result);

                if ($resultCNPJ > 0){

                   $exibir = true;
                   $resultado = "";

                   foreach($result as $pessoa){

                       $resultado .= "<tr>";
                       $resultado .= "<td>".$pessoa['razao_social']."</td>";
                       $resultado .= "<td>".$pessoa['cnpj']."</td>";
                       $resultado .= "<td>".$pessoa['email']."</td>";
                       $resultado .= "<td>
                                     <form action='?perfil=evento&p=pj_edita' method='post'>
                                        <input type='hidden' name='idPj' value='".$pessoa['id']."'>
                                        <input type='submit' name='carregar' class='btn btn-primary' name='selecionar' value='Selecionar'>
                                     </form>
                               </td>";
                       $resultado .= "</tr>";
                   }


               }else {
                   $exibir = false;
                   $resultado = "<td colspan='4'>
                        <span style='margin: 50% 40%;'>Sem resultados</span>
                      </td>
                      <td>
                        <form method='post' action='?perfil=evento&p=pj_cadastro'>
                            <input type='hidden' name='cnpj' value='$procurar'>
                            <button class=\"btn btn-primary\" name='adicionar' type='submit'>
                                <i class=\"glyphicon glyphicon-plus\">        
                                </i>Adicionar
                            </button>
                        </form>
                      </td>";
               }

            }   

    }

}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Pesquisar Pessoa Jurídica</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pj_pesquisa" method="post">
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="CNPJ" name="procurar" value="<?=$procurar?>" id="cnpj">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i> Procurar</button>
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
                                            <th>Razão Social</th>
                                            <th>CNPJ</th>
                                            <th>E-mail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($exibir){
                                                echo $resultado;
                                            }elseif(!$exibir){
                                                echo $resultado;
                                            }else{
                                                echo $resultado;
                                            }
                                        ?>
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

<script>

    $("#CNPJ").mask('99.999.999/9999-99', {reverse: true});
    
</script>