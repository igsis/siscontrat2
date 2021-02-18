<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

if (!isset($_GET['atracao'])) {
    echo "<script>window.location.href = '?perfil=evento&p=atracoes_lista'</script>";
}

$atracao_id = $_GET['atracao'];
$exibir = ' ';
$resultado = "<td></td>";
$procurar = NULL;
$tipoDocumento = null;

if (isset($_POST['procurar']) || isset($_POST['passaporte'])) {
    $botaoAdd = "<button class='btn btn-primary' name='adicionar' id='adicionar' type='submit'>
                                <i class='glyphicon glyphicon-plus'>        
                                </i>Adicionar
                            </button>";
    $actionCadastra = "?perfil=evento&p=integrantes_cadastro";

    $procurar = $_POST['procurar'] ?? $_POST['passaporte'];
    $tipoDocumento = $_POST['tipoDocumento'] ?? false;

    if ($procurar != NULL) {
        $queryCPF = "SELECT  `id`, `nome`, `rg`, `cpf_passaporte`
                         FROM `integrantes`
                         WHERE `cpf_passaporte` = '$procurar'";

        if ($result = mysqli_query($con, $queryCPF)) {
            $resultCPF = mysqli_num_rows($result);

            if ($resultCPF > 0) {

                $exibir = true;
                $resultado = "";

                foreach ($result as $integrante) {
                    $sqlConsultaIntegrante = "SELECT integrante_id FROM atracao_integrante
                                                WHERE integrante_id = '{$integrante['id']}' AND atracao_id = '$atracao_id'";
                    $cadastrado = $con->query($sqlConsultaIntegrante)->num_rows;

                    $resultado .= "<tr>";
                    $resultado .= "<td>" . $integrante['nome'] . "</td>";
                    $resultado .= "<td>" . $integrante['cpf_passaporte'] . "</td>";
                    $resultado .= "<td>" . $integrante['rg'] . "</td>";
                    if ($cadastrado) {
                        $resultado .= "<td>
                                             <span class='label label-danger'>
                                                <i class='glyphicon glyphicon-lock'>        
                                                </i> Já cadastrado nesta Atração
                                            </span>
                                       </td>";
                    } else {
                        $resultado .= "<td>
                                         <form action='$actionCadastra' method='post'>
                                            <input type='hidden' name='integrante_id' value='" . $integrante['id'] . "'>
                                            <input type='hidden' name='idAtracao' value='$atracao_id'>
                                            <input type='hidden' name='_method' value='cadastra'>
                                            $botaoAdd                                        
                                         </form>
                                   </td>";
                    }
                    $resultado .= "</tr>";
                }


            } else {
                $exibir = false;
                $resultado = "<td colspan='4'>
                        <span style='margin: 50% 40%;'>Sem resultados</span>
                      </td>
                      <td>
                        <form method='post' action='$actionCadastra'>
                            <input type='hidden' name='idAtracao' value='$atracao_id'>
                            <input type='hidden' name='documento' value='$procurar'>
                            <input type='hidden' name='_method' value='cadastra'>
                            $botaoAdd
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
        <h2 class="page-header">Integrantes</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Procurar integrante</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=integrantes_pesquisa&atracao=<?= $atracao_id ?>" method="post" id="formulario">

                            <label for="tipoDocumento">Tipo de documento: </label>
                            <label class="radio-inline">
                                <input type="radio" name="tipoDocumento" value="1" checked>CPF
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="tipoDocumento" value="2">Passaporte
                            </label>



                            <div class="form-group">
                                <label for="">Pesquisar:</label>
                                <div class="form-group">
                                    <label for="cpf" id="textoDocumento">CPF *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" minlength=14 name="procurar"
                                               value="<?= $procurar ?>" id="cpf" data-mask="000.000.000-00" minlength="14">
                                        <input type="text" class="form-control" name="passaporte" id="passaporte"
                                               value="<?= $procurar ?>" maxlength="10">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">
                                                <i class="glyphicon glyphicon-search"></i> Procurar
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="panel panel-default">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th id="trocaDoc">CPF / Passaporte</th>
                                        <th>RG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= $resultado ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=evento&p=integrantes_lista&atracao=<?= $atracao_id ?>"
                           class="btn btn-default">Voltar</a>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
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