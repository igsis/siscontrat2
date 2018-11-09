<?php

$con = bancoMysqli();
// include "includes/menu_interno.php";
unset($_SESSION['idPf_pedido']);

include "includes/menu_pf.php";

$exibir = ' ';
$resultado = "<td></td>";
$procurar = NULL;




if (isset($_POST['procurar']) || isset($_POST['passaporte'])){

    $procurar = $_POST['procurar'] ?? $_POST['passaporte'];
    $tipoDocumento = $_POST['tipoDocumento'];

    if ($procurar != NULL ) {

        if ($tipoDocumento == 1){

            $queryCPF = "SELECT  id, nome, cpf, email
                         FROM siscontrat.`pessoa_fisicas`
                         WHERE cpf = '$procurar'";

           if ($result = mysqli_query($con,$queryCPF)) {

               $resultCPF = mysqli_num_rows($result);

               if ($resultCPF > 0){

                   $exibir = true;
                   $resultado = "";

                   foreach($result as $pessoa){

                       $resultado .= "<tr>";
                       $resultado .= "<td>".$pessoa['nome']."</td>";
                       $resultado .= "<td>".$pessoa['cpf']."</td>";
                       $resultado .= "<td>".$pessoa['email']."</td>";
                       $resultado .= "<td>
                                     <form action='?perfil=evento&p=pf_edita' method='post'>
                                        <input type='hidden' name='idPf' value='".$pessoa['id']."'>
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
                        <form method='post' action='?perfil=evento&p=pf_cadastro'>
                            <input type='hidden' name='documentacao' value='$procurar'>
                            <input type='hidden' name='tipoDocumento' value='$tipoDocumento'>
                            <button class=\"btn btn-primary\" name='adicionar' type='submit'>
                                <i class=\"glyphicon glyphicon-plus\">        
                                </i>Adicionar
                            </button>
                        </form>
                      </td>";

               }

            }
        }else {
            if ($tipoDocumento == 2) {

                $queryPassaporte = "SELECT id,nome,passaporte,email
                            FROM siscontrat.`pessoa_fisicas`
                            WHERE passaporte = '$procurar'";

                            echo "<pre>".$queryPassaporte ."</pre>";

                if ($result = mysqli_query($con, $queryPassaporte)) {

                    $resultPassaporte = mysqli_num_rows($result);

                    if ($resultPassaporte > 0) {

                        $exibir = true;
                        $resultado = "";

                        foreach($result as $pessoa) {
                            $resultado .= "<tr>";
                            $resultado .= "<td>" . $pessoa['nome'] . "</td>";
                            $resultado .= "<td>" . $pessoa['passaporte'] . "</td>";
                            $resultado .= "<td>" . $pessoa['email'] . "</td>";
                            $resultado .= "<td>
                                     <form action='#' method='post'>
                                        <input type='hidden' name='idPessoa' value='" . $pessoa['id'] . "'>
                                        <input class='btn btn-primary' name='selecionar' value='Selecionar'>
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
                        <form method='post' action='?perfil=evento&p=pf_cadastro'>
                            <input type='hidden' name='documentacao' value='$procurar'>
                            <input type='hidden' name='tipoDocumento' value='$tipoDocumento'>
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
                        <h3 class="box-title">Procurar pessoa fisica</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pf_cadastro_pesquisa" method="post">
                            <label for="tipoDocumento">Tipo de documento: </label>
                            <label class="radio-inline">
                               <input type="radio" name="tipoDocumento" value="1" checked>CPF
                            </label>
                            <label class="radio-inline">
                               <input type="radio" name="tipoDocumento" value="2">Passaporte
                            </label>
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="procurar" value="<?=$procurar?>" id="cpf">
                                    <input type="text" class="form-control" name="passaporte" value="<?=$procurar?>" >
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
                                            <th>Nome</th>
                                            <th>CPF</th>
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

    let tipos = document.querySelectorAll("input[type='radio'][name='tipoDocumento']");
    let passaporte = document.querySelector("input[name='passaporte']");
    let procurar = document.querySelector("input[name='procurar']");

    passaporte.style.display = 'none' 

    for (const tipo of tipos) {
        tipo.addEventListener('change', e => {

            passaporte.value = ''
            procurar.value = ''

            if(e.target.value == 1){
                passaporte.style.display = 'none'
                procurar.style.display = 'block'
            }else{
                passaporte.style.display = 'block'
                procurar.style.display = 'none'
            }            
        })
    }

    $("input[name='procurar']").mask('000.000.000-00', {reverse: true});
  
</script>