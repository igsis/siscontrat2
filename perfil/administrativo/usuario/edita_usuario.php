<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $nome = addslashes($_POST['nome']);
    $jovemMonitor = $_POST['jovem_monitor'];
    $rgRf = $_POST['rgrf_usuario'];
    $telefone = $_POST['tel_usuario'];
    $email = addslashes($_POST['email']);
    $usuario = addslashes($_POST['usuario']);
    $perfil = addslashes($_POST['perfil']);

    if ($jovemMonitor == 0) {
        // fazer um in_array() depois que ficar definido os modulos que terá acesso a eventos
        $fiscal = 1;
    } else {
        $fiscal = 0;
    }

    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO usuarios (nome_completo, jovem_monitor, rf_rg, usuario, email, telefone, perfil_id, fiscal)
        VALUES ('$nome', '$jovemMonitor','$rgRf', '$usuario', '$email', '$telefone', '$perfil', '$fiscal')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Usuário cadastrado com sucesso!");
            $idUsuario = recuperaUltimo('usuarios');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de usuário! Tente novamente.");
        }
    }

    if (isset($_POST['edita'])) {
        $idUsuario = $_POST['idUsuario'];

        $sql = "UPDATE usuarios SET nome_completo = '$nome', jovem_monitor = '$jovemMonitor', rf_rg = '$rgRf', usuario = '$usuario', email = '$email',
        telefone = '$telefone', perfil_id = '$perfil', fiscal = '$fiscal' WHERE id = '$idUsuario'";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Usuário editado com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Erro ao salvar o usuário! Tente novamente.");
        }
    }
}

if (isset($_POST['reset'])){
    $idUsuario = $_POST['idUsuario'];
    $senha = MD5("siscontrat2019");
    $sql_reset = "UPDATE usuarios SET senha = '$senha' WHERE id = '$idUsuario'";
    if(mysqli_query($con,$sql_reset)){
        gravarLog($sql_reset);
        $mensagem = mensagem("success", "Senha reiniciada com sucesso para: siscontrat2019");
    } else {
        $mensagem = mensagem("danger", "Erro ao reiniciar a senha! Tente novamente.");
    }
}

if (isset($_POST['carregar'])) {
    $idUsuario = $_POST['idUsuario'];
}

$usuario = recuperaDados('usuarios', 'id', $idUsuario);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Usuário</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usuários</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=usuario&sp=edita_usuario" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nome">Nome Completo *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required
                                           value="<?= $usuario['nome_completo'] ?>">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="tipo">É estagiário/jovem monitor? *</label> <br>
                                    <label><input type="radio" name="jovem_monitor"
                                                  id="jovem_monitor" <?= $usuario['jovem_monitor'] == 1 ? 'checked' : NULL ?>
                                                  value="1"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="jovem_monitor" id="jovem_monitor"
                                                  value="0" <?= $usuario['jovem_monitor'] == 0 ? 'checked' : NULL ?>
                                                  value="1"> Não </label>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="rf_usuario">RF/RG</label>
                                    <input type="text" id="rgrf_usuario" name="rgrf_usuario" class="form-control"
                                           value="<?= $usuario['rf_rg'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="rf_usuario">Usuário *</label>
                                    <input type="text" id="usuario" name="usuario" class="form-control" maxlength="7"
                                           required readonly value="<?= $usuario['usuario'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="email">E-mail *</label>
                                    <input type="email" id="email" name="email" class="form-control" maxlength="100"
                                           required value="<?= $usuario['email'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="tel_usuario">Telefone *</label>
                                    <input data-mask="(00) 0000-00000" type="text" id="tel_usuario" name="tel_usuario"
                                           class="form-control" required value="<?= $usuario['telefone'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="perfil">Perfil </label> <br>
                                    <select class="form-control" id="perfil" name="perfil">
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("perfis", $usuario['perfil_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=usuario&sp=usuario_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <button type="submit" name="reset" class="btn btn-warning">Resetar senha</button>
                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?= $idUsuario ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">Salvar
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div>

<script>
    function habilitaCampo(id) {
        if (document.getElementById(id).disabled == true) {
            document.getElementById(id).disabled = false
        }
    }

    function desabilitarCampo(id) {
        if (document.getElementById(id).disabled == false) {
            document.getElementById(id).disabled = true
        }
    }

    /* function habilitarRadio (valor) {
         if (valor == 2) {
             document.status.disabled = false;
         } else {
             document.status.disabled = true;
         }
     }*/

    function geraUsuarioRf() {

        // pega o valor que esta escrito no RF
        let usuarioRf = document.querySelector("#rgrf_usuario").value;

        // tira os pontos do valor, ficando apenas os numeros
        usuarioRf = usuarioRf.replace(/[^0-9]/g, '');
        usuarioRf = parseInt(usuarioRf);

        // adiciona o d antes do rf
        usuarioRf = "d" + usuarioRf;

        // limita o rf a apenas o d + 6 primeiros numeros do rf
        let usuario = usuarioRf.substr(0, 7);

        // passa o valor para o input
        document.querySelector("[name='usuario']").value = usuario;
    }


    function geraUsuarioRg() {

        // pega o valor que esta escrito no RG
        let usuarioRg = document.querySelector("#rgrf_usuario").value;

        // tira os pontos do valor, ficando apenas os numeros
        usuarioRg = usuarioRg.replace(/[^0-9]/g, '');
        usuarioRg = parseInt(usuarioRg);

        // adiciona o x antes do rg
        usuarioRg = "x" + usuarioRg;

        // limita o rg a apenas o d + 6 primeiros numeros do rf
        let usuario = usuarioRg.substr(0, 7);

        // passa o valor para o input
        document.querySelector("[name='usuario']").value = usuario;

    }

    $("input[name='jovem_monitor']").change(function () {
        $('#rgrf_usuario').attr("disabled", false);

        var jovemMonitor = document.getElementsByName("jovem_monitor");

        for (let i = 0; i < jovemMonitor.length; i++) {
            if (jovemMonitor[i].checked) {
                var escolhido = jovemMonitor[i].value;

                var mascara = '000.000.0';
                if (escolhido == 1) {
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').unmask(mascara);
                    $('#rgrf_usuario').keypress(function (event) {
                        geraUsuarioRg();
                    });
                    $('#rgrf_usuario').blur(function (event) {
                        geraUsuarioRg();
                    });

                } else if (escolhido == 0) {
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').mask(mascara);
                    $('#rgrf_usuario').keypress(function (event) {
                        geraUsuarioRf();
                    });
                    $('#rgrf_usuario').blur(function (event) {
                        geraUsuarioRf();
                    });
                }
            }
        }
    })


</script>
