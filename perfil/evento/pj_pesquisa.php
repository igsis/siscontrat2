<?php

$con = bancoMysqli();
// include "includes/menu_interno.php";
unset($_SESSION['idPj_pedido']);

include "includes/menu_interno.php";

$exibir = ' ';
$resultado = "<td></td>";
$procurar = NULL;


if (isset($_POST['troca_pj'])) {
    $idPj = $_POST['idProponente'];
    $idPedido = $_POST['idPedido'];
}


if (isset($_POST['procurar'])){
    $procurar = $_POST['procurar'];
    $idPj = $_POST['idProponente'] ?? NULL;
    $idPedido = $_POST['idPedido'] ?? NULL;

    if($idPedido != NULL && $idPj != NULL){
            $botaoSelecionar = "<input type='submit' name='trocaPj' class='btn btn-primary' value='Selecionar como novo proponente'>";
            $botaoAdd = "<button class='btn btn-primary' name='adicionaPj' type='submit'>
                                <i class='glyphicon glyphicon-plus'>        
                                </i>Adicionar
                            </button>";
            $actionEdita = "?perfil=evento&p=pedido_proponente";
            $actionCadastra = "?perfil=evento&p=pj_cadastro";
    }else{
            $botaoSelecionar = "<input type='submit' class='btn btn-primary' name='selecionar' value='Selecionar'>";
            $botaoAdd = "<button class='btn btn-primary' name='adicionar' id='adicionar' type='submit'>
                                <i class='glyphicon glyphicon-plus'>        
                                </i>Adicionar
                            </button>";
            $actionEdita = "?perfil=evento&p=pj_edita";
            $actionCadastra = "?perfil=evento&p=pj_cadastro";
        }

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
                                     <form action='$actionEdita' method='post'>
                                        <input type='hidden' name='idPj' value='".$pessoa['id']."'>
                                        <input type='hidden' name='idPedido' value='$idPedido'>
                                        $botaoSelecionar
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
                        <form method='post' action='$actionCadastra'>
                            <input type='hidden' name='cnpj' value='$procurar'>
                            <input type='hidden' name='idPedido' value='$idPedido'>
                           $botaoAdd
                        </form>
                      </td>";
               }

            }   

    }

}

?>
<script>
    $(document).ready(function () {
        $("#cnpj").mask('00.000.000/0000-00', {reverse: true});
    });
</script>

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
                                <label for="procurar">Pesquisar CNPJ:</label>
                                <div class="input-group">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido ?? NULL ?>">
                                    <input type="hidden" name="idProponente" value="<?=$idPj ?? NULL ?>">
                                    <input type="text" class="form-control" id="cnpj" name="procurar" value="<?=$procurar?>" placeholder="00.000.000/0000-00" data-mask="00.000.000/0000-00">
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
    function validarCNPJ(cnpj) {
        if (cnpj.length !== 14)
        {
            return false;
        }
        // Elimina CNPJs invalidos conhecidos
        if (cnpj == "11111111111111" ||
            cnpj == "22222222222222" ||
            cnpj == "33333333333333" ||
            cnpj == "44444444444444" ||
            cnpj == "55555555555555" ||
            cnpj == "66666666666666" ||
            cnpj == "77777777777777" ||
            cnpj == "88888888888888" ||
            cnpj == "99999999999999")
            return false;

        // Valida DVs
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;

        return true;

    }

    function validacao(){
        var divCNPJ = document.querySelector('#divCNPJ');
        var cnpj = document.querySelector('#cnpj').value;

        // tira os pontos do valor, ficando apenas os numeros
        cnpj = cnpj.replace(/[^\d]+/g,'');

        var validado = validarCNPJ(cnpj);

        if(!validado){
            alert("CNPJ inválido!");
            document.querySelector("#adicionar").disabled = true;
        }else if(validado){
            document.querySelector("#adicionar").disabled = false;
        }
    }

    $(document).ready(function () {
        if(document.querySelector("#cnpj").value != ""){
            validacao();
        }
    });
</script>
