<?php

include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['tipoRepresentanteTroca'])){
    $tipoRepresentante = $_POST['tipoRepresentanteTroca'];
    $_SESSION['idPj'] = $_POST['idPj'];
    $idPj = $_SESSION['idPj'];
}

if (isset($_POST['tipoRepresentante']) && isset($_POST['idPj'])) {
    $tipoRepresentante = $_POST['tipoRepresentante'];
    $_SESSION['idPj'] = $_POST['idPj'];
    $idPj = $_SESSION['idPj'];

    //echo $tipoRepresentante;
}

if (isset($_POST['pesquisa'])) {

    $cpf = $_POST['cpf'];

    $sqlRepre = "SELECT * FROM representante_legais WHERE cpf = '$cpf' LIMIT 1";
    $representante = $conn->query($sqlRepre)->fetch();

    if ($representante != null && count($representante) > 0) {
        $idRepresentante = $representante['id'];
        //echo "teste " . $idRepresentante;

        $sqlPj = "SELECT representante_legal1_id, representante_legal2_id FROM pessoa_juridicas WHERE id = '$idPj' AND (representante_legal1_id = '$idRepresentante' 
                                                  OR representante_legal2_id = '$idRepresentante')";
        $pj = $conn->query($sqlPj)->fetch();

        if ($pj != null && count($pj) > 0) {
            if (isset($pj['representante_legal1_id']) && $idRepresentante == $pj['representante_legal1_id']) {
                echo "<script>
                       swal('A pessoa fisíca " . $representante['nome'] . " cadastrada com esse cpf é seu representante legal 1!', '', 'warning')
                        .then(() => {
                            desabilitaSelecionar(); 
                        });
                     </script>";

            } else if (isset($pj['representante_legal2_id']) && $idRepresentante == $pj['representante_legal2_id']) {
                echo "<script>                     
                      swal('A pessoa fisíca " . $representante['nome'] . " cadastrada com esse cpf é seu representante legal 2!', '', 'warning')
                        .then(() => {
                            desabilitaSelecionar(); 
                        });               
                    </script>";
            }
        }
        $mensagem = "<form method='post' action='?perfil=evento&p=representante_edita'>
                        <tr>
                            <td>" . $representante['nome'] . "</td>
                            <td>" . $representante['cpf'] . "</td>
                            <td>" . $representante['rg'] . "</td>
                            <td>
                                <input type='hidden' name='idPj' value='" . $idPj . "'>
                                <input type='hidden' name='idRepresentante' value='" . $representante['id'] . "'>
                                <input type='hidden' name='nome' value='" . $representante['nome'] . "'>
                                <input type='hidden' name='cpf' value='" . $representante['cpf'] . "'>
                                <input type='hidden' name='rg' value='" . $representante['rg'] . "'>
                                <input type='hidden' name='tipoRepresentante' value='" . $tipoRepresentante . "'>
                                <button type='submit' class='btn btn-primary' name='carregar' id='selecionar'>Selecionar</button>
                            </td>
                        </tr>
                    </form>";

    } else {
        $mensagem = "<form action='?perfil=evento&p=representante_cadastro' method='post'>
                        <tr>
                            <td>Representante não cadastrado</td>
                            <td>
                                <input type='hidden' name='documentacao' value='" . $cpf . "'>
                                <input type='hidden' name='tipoRepresentante' value='" . $tipoRepresentante . "'>
                                <button type='submit' class='btn btn-primary' id='adicionar' name='adicionar'><i class='glyphicon glyphicon-plus'></i> Adicionar</button>
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
                        <h3 class="box-title">Procurar pessoa fÍsica</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=representante_busca" method="post">
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength=14 name="cpf"
                                           value="<?= empty($cpf) ? '' : $cpf ?>" id="cpf" data-mask="000.000.000-00"
                                           placeholder="Digite o CPF aqui. . . . ">
                                    <span class="input-group-btn">
                                        <input type="hidden" name="idPj" value="<?= $idPj ?>">
                                        <input type="hidden" name="tipoRepresentante" value="<?= $tipoRepresentante ?>">
                                        <button class="btn btn-default" name="pesquisa" type="submit"><i
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
                                    <th>CPF</th>
                                    <th>RG</th>
                                <tr></tr>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($mensagem)) {
                                    echo $mensagem;
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <form action="?perfil=evento&p=pj_edita" method="post">
                                <button type="submit" id="idPj" name="idPj" value="<?= $idPj ?>"
                                        class="btn btn-default">Voltar
                                </button>
                            </form>
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

    function desabilitaSelecionar() {

        let testeee = document.querySelector('#selecionar');
        testeee.disabled = true;

        console.log(testeee);
    }


    function TestaCPF(cpf) {
        var Soma;
        var Resto;
        var strCPF = cpf;
        Soma = 0;

        if (strCPF == "11111111111" ||
            strCPF == "22222222222" ||
            strCPF == "33333333333" ||
            strCPF == "44444444444" ||
            strCPF == "55555555555" ||
            strCPF == "66666666666" ||
            strCPF == "77777777777" ||
            strCPF == "88888888888" ||
            strCPF == "99999999999")
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
        var divCPF = document.querySelector('#divCPF');
        var strCPF = document.querySelector('#cpf').value;

        if (strCPF != null) {
            // tira os pontos do valor, ficando apenas os numeros
            strCPF = strCPF.replace(/[^0-9]/g, '');

            var validado = TestaCPF(strCPF);

            if (!validado) {
                swal("CPF inválido!", "", "error");
                $("#adicionar").attr("disabled", true);
            } else {
                $("#adicionar").attr("disabled", false);
            }
        }
    }

    $(document).ready(function () {
        if ((document.querySelector("#cpf").value != "")) {
            validacao();
        }
    });

</script>