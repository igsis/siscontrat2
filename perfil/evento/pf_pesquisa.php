<?php

$con = bancoMysqli();
include "includes/menu_interno.php";
unset($_SESSION['idPf_pedido']);


$exibir = ' ';
$resultado = "<td></td>";
$procurar = NULL;
$tipoDocumento = null;


if (isset($_POST['procurar']) || isset($_POST['passaporte'])){

    $procurar = $_POST['procurar'] ?? $_POST['passaporte'];
    $tipoDocumento = $_POST['tipoDocumento'] ?? false;

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
                            <button class=\"btn btn-primary\" name='adicionar' type='submit' id='adicionar'>
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
                                     <form action='?perfil=evento&p=pf_edita' method='post'>
                                        <input type='hidden' name='idPessoa' value='" . $pessoa['id'] . "'>
                                        <input type='submit' class='btn btn-primary' name='selecionar' value='Selecionar'>
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
                        <form action="?perfil=evento&p=pf_pesquisa" method="post">
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
                                    <label for="cpf">CPF *</label>
                                    <input type="text" class="form-control" minlength=14 name="procurar" value="<?=$procurar?>" id="cpf" data-mask="000.000.000-00" >
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
                                            <th id="trocaDoc">CPF</th>
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
    let trocaDoc = document.querySelector("#trocaDoc");

    
    if (`<?=$tipoDocumento?>` == 2) {
        trocaDoc.innerHTML = 'Passaporte'
        tipos[1].checked = true
        passaporte.style.display = 'block'
        passaporte.disabled = false
        procurar.disabled = true
        procurar.style.display = 'none'

    }else{
        passaporte.style.display = 'none' 
        passaporte.disabled = true   

    }


    for (const tipo of tipos) {
        tipo.addEventListener('change', e => {

            passaporte.value = ''
            procurar.value = ''

            if(e.target.value == 1){
                passaporte.style.display = 'none'
                procurar.disabled = false
                passaporte.disabled = true                
                procurar.style.display = 'block'
            }else{
                passaporte.style.display = 'block'
                passaporte.disabled = false
                procurar.disabled = true
                procurar.style.display = 'none'
                
            }            
        })
    }

    function TestaCPF(cpf) {
        var Soma;
        var Resto;
        var strCPF = cpf;
        Soma = 0;

        if (strCPF == "00000000000" ||
            strCPF == "11111111111" ||
            strCPF == "22222222222" ||
            strCPF == "33333333333" ||
            strCPF == "44444444444" ||
            strCPF == "55555555555" ||
            strCPF == "66666666666" ||
            strCPF == "77777777777" ||
            strCPF == "88888888888" ||
            strCPF == "99999999999")
            return false;

        for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;

        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;

        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
        return true;
    }

    function validacao(){
        var divCPF = document.querySelector('#divCPF');
        var strCPF = document.querySelector('#cpf').value;

        // tira os pontos do valor, ficando apenas os numeros
        strCPF = strCPF.replace(/[^0-9]/g, '');

        var validado = TestaCPF(strCPF);

        if(!validado){
            alert("CPF inválido!");
           document.querySelector("#adicionar").disabled = true;
        }else{
            document.querySelector("#adicionar").disabled = false;
        }
    }

    $(document).ready(function () {
        if(document.querySelector("#cpf").value != ""){
            validacao();
        }
    });
</script>