<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idAtracao = null;
$exibir = ' ';
$resultado = "<td></td>";
$procurar = NULL;
$tipoDocumento = null;

if (isset($_POST['procurar']) || isset($_POST['passaporte'])) {
    $idAtracao = $_POST['idAtracao'] ?? NULL;


    $procurar = $_POST['procurar'] ?? $_POST['passaporte'];
    $tipoDocumento = $_POST['tipoDocumento'] ?? false;

    if ($idAtracao != null) {
        $botaoSelecionar = "<input type='submit' name='cadastraLider' class='btn btn-primary' value='Selecionar'>";
        $botaoAdd = "<button class='btn btn-primary' name='adicionarLider' type='submit'>
                                <i class='glyphicon glyphicon-plus'>        
                                </i>Adicionar
                            </button>";
    } else {
        $botaoSelecionar = "<input type='submit' class='btn btn-primary' name='selecionar' value='Selecionar'>";
        $botaoAdd = "<button class='btn btn-primary' name='adicionar' type='submit'>
                                <i class='glyphicon glyphicon-plus'>        
                                </i>Adicionar
                            </button>";
    }

    if ($procurar != NULL) {
        if ($tipoDocumento == 1) {
            $sqlCPF = "SELECT id,nome,cpf,email
            FROM pessoa_fisicas
            where cpf = '$procurar'";

            $query_cpf = mysqli_query($con,$sqlCPF);


            if ($array_cpf = mysqli_fetch_array($query_cpf)){ // executa o valor o array da querry.
                var_dump($array_cpf);
                $num_cpf = mysqli_num_rows($query_cpf);
                if ($num_cpf > 0) {
                    $exibir = true;
                    $resultado = "";

                    foreach ($query_cpf as $pessoa) {

                        $resultado .= "<tr>";
                        $resultado .= "<td>" . $pessoa['nome'] . "</td>";
                        $resultado .= "<td>" . $pessoa['cpf'] . "</td>";
                        $resultado .= "<td>" . $pessoa['email'] . "</td>";
                        $resultado .= "<td>
                                     <form action='#' method='post'> 
                                        <input type='hidden' name='idLider' value='" . $pessoa['id'] . "'>
                                        <input type='hidden' name='idAtracao' value='$idAtracao'>
                                        $botaoSelecionar                                        
                                     </form>
                               </td>";
                        $resultado .= "</tr>";
                    }
                } else {
                    $exibir = false;
                    $resultado = "<td colspan='4'>
                        <span style='margin: 50% 40%;'>Sem resultados</span>
                      </td>
                      <td>
                        <form method='post' action='#'>
                            <input type='hidden' name='idAtracao' value='$idAtracao'>
                            <input type='hidden' name='documentacao' value='$procurar'>
                            <input type='hidden' name='tipoDocumento' value='$tipoDocumento'>
                            $botaoAdd
                        </form>
                 </td>";
                }
            }
        } else {
            if ($tipoDocumento == 2 ){
                $sqlPassaporte = "SELECT * id,nome,cpf,email 
                FROM pessoa_fisicas 
                where passaporte = '$procurar'";

                $query_passaporte = mysqli_query($con,$sqlPassaporte);

                if($array_passaporte = mysqli_fetch_array($query_passaporte)){
                    $num_passaporte = mysqli_num_rows($query_passaporte);
                    if ($num_passaporte > 0 ){
                        $exibir = true;
                        $resultado = "";

                        foreach ($query_passaporte as $pessoa){
                            $resultado .= "<tr>";
                            $resultado .= "<td>". $pessoa['nome'] . "</td>";
                            $resultado .= "<td>". $pessoa['passaporte'] . "</td>";
                            $resultado .= "<td>". $pessoa['email'] . "</td>";
                            $resultado .= "<td>
                                        <form action='' method='post'>
                                        <input type='hidden' name='idPessoa' value='" . $pessoa['id'] . "'>
                                        <input type='submit' class='btn btn-primary' name='selecionar' value='selecionar'>
                                        </form>
                                        </td>";
                            $resultado .= "</tr>";
                        }
                    } else {
                        $exibir = false;
                        $resultado = "<td colspan='4'>
                        <span style='...'>Sem Resultado</span>
                        </td>
                        <td>
                        <form method='post' action='?perfil=evento&p=adiciona_lider'>
                        <input type='hidden' name='documentacao' value='$procurar'>
                        <input type='submit' name='tipoDumentacao' value='$tipoDocumento'>
                        <button class='btn btn-primary'  name='adicionar' type='submit'>
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
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Lider</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Procurar pelo Lider</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pesquisa_lider" method="post" id="formulario">
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
                                    <label for="cpf" id="textoDocumento">CPF *</label>
                                    <input type="text" class="form-control" minlength=14 name="procurar"
                                           value="<? $procurar ?>" id="cpf" data-mask="000.000.000-00" minlength="14">
                                    <input type="text" class="form-control" name="passaporte" id="passaporte"
                                           value="<? $procurar ?>" maxlength="10">

                                    <span class="input-group-btn">
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                        <input type="hidden" name="idAtracao" value="<?= $idAtracao ?? NULL ?>">
                                        <button class="btn btn-default" type="submit"><i
                                                    class="glyphicon glyphicon-search"></i> Procurar</button>
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

                                </tbody>
                            </table>
                        </div>
                        <script>
                            let tipos = document.querySelectorAll("input[type='radio'][name='tipoDocumento']");
                            let passaporte = document.querySelector("input[name='passaporte']");
                            let procurar = document.querySelector("input[name='procurar']");
                            let trocaDoc = document.querySelector("#trocaDoc");
                            for (const tipo of tipos) {
                                tipo.addEventListener('change', tp => {

                                    const nulo = null;

                                    passaporte.value = nulo
                                    procurar.value = nulo

                                    if (tp.target.value == 1) {
                                        passaporte.style.display = 'none'
                                        procurar.disabled = false
                                        passaporte.disabled = true
                                        procurar.style.display = 'block'
                                        procurar.value = ''
                                        $('#textoDocumento').text('CPF *')
                                    } else {
                                        passaporte.style.display = 'block'
                                        passaporte.disabled = false
                                        passaporte.value = ''
                                        procurar.disabled = true
                                        procurar.style.display = 'none'
                                        $('#textoDocumento').text('Passaporte *')
                                    }
                                })
                            }

                            if (`<?=$tipoDocumento?>` == 2) {
                                trocaDoc.innerHTML = 'Passaporte'
                                tipos[1].checked = true
                                passaporte.style.display = 'block'
                                passaporte.disabled = false
                                procurar.disabled = true
                                procurar.style.display = 'none'
                            } else {
                                passaporte.style.display = 'none'
                                passaporte.disabled = true
                            }

                            /**
                             * @return {boolean}
                             */
                            function TestaCPF(cpf) {
                                var Soma;
                                var Resto;
                                var strCPF = cpf;
                                Soma = 0;

                                if (strCPF === "11111111111" ||
                                    strCPF === "22222222222" ||
                                    strCPF === "33333333333" ||
                                    strCPF === "44444444444" ||
                                    strCPF === "55555555555" ||
                                    strCPF === "66666666666" ||
                                    strCPF === "77777777777" ||
                                    strCPF === "88888888888" ||
                                    strCPF === "99999999999")
                                    return false;

                                for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
                                Resto = (Soma * 10) % 11;

                                if ((Resto == 10) || (Resto == 11)) Resto = 0;
                                if (Resto != parseInt(strCPF.substring(9, 10))) return false;

                                Soma = 0;
                                for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
                                Resto = (Soma * 10) % 11;

                                if ((Resto == 10) || (Resto == 11)) Resto = 0;
                                if (Resto != parseInt(strCPF.substring(10, 11))) return false;
                                return true;
                            }

                            function validacao() {
                                var strCPF = document.querySelector('#cpf').value;

                                if (strCPF != null) {
                                    // tira os pontos do valor, ficando apenas os numeros
                                    strCPF = strCPF.replace(/[^0-9]/g, '');

                                    var validado = TestaCPF(strCPF);

                                    if (!validado)
                                        $("#adicionar").attr("disabled", true);
                                    else
                                        $("#adicionar").attr("disabled", false);

                                }
                            }


                            $('#formulario').submit(function (event) {
                                var strCPF = document.querySelector('#cpf').value;

                                if (strCPF !== '' && `<?=$tipoDocumento?>` != 2) {
                                    console.log(`<?=$tipoDocumento?>`)
                                    // tira os pontos do valor, ficando apenas os numeros
                                    strCPF = strCPF.replace(/[^0-9]/g, '');

                                    var validado = TestaCPF(strCPF);

                                    if (!validado) {
                                        event.preventDefault()
                                        alert("CPF inválido")
                                    }
                                }
                            })


                        </script>
                    </div>
                </div>
            </div>
    </section>
</div>
