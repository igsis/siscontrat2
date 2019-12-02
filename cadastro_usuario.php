<?php
include "funcoes/funcoesGerais.php";
require "funcoes/funcoesConecta.php";

$con = bancoMysqli();

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_verifica_email.php';

if (isset($_POST['cadastra'])) {
    $nome = $_POST['nome'];
    $jovemMonitor = $_POST['jovem_monitor'];
    $rgRf = $_POST['rgrf_usuario'];
    $telefone = $_POST['tel_usuario'];
    $email = $_POST['email'];
    $usuario = $_POST['usuario'];
    $perfil = $_POST['perfil'];

    $sql_perfil = "SELECT * FROM perfis WHERE token = '$perfil'";
    $query_perfil = mysqli_query($con, $sql_perfil);

    if (mysqli_num_rows($query_perfil) > 0) {
        $perfilSelecioado = mysqli_fetch_assoc($query_perfil);
        $idPerfil = $perfilSelecioado['id'];
        $acertou = 1;
    } else {
        $acertou = 0;
    }

    if ($acertou) {
        $sqlModulosEventos = "SELECT DISTINCT p.token FROM modulo_perfis mp 
                              INNER JOIN modulos m on mp.modulo_id = m.id 
                              INNER JOIN perfis p on mp.perfil_id = p.id
                              WHERE p.publicado = 1 AND m.id = 2";

        $queryModulosEventos = mysqli_query($con, $sqlModulosEventos);
        $modulosEventos = mysqli_fetch_array($queryModulosEventos);

        $fiscal = 0;

        if ($jovemMonitor == 0) {
            while ($token = mysqli_fetch_array($queryModulosEventos)) {
                if ($token['token'] == $perfil)
                    $fiscal = 1;

            }
        }

        if (isset($_POST['cadastra'])) {
            $sql = "INSERT INTO usuarios (nome_completo, jovem_monitor, rf_rg, usuario, email, telefone, perfil_id, fiscal)
        VALUES ('$nome', '$jovemMonitor','$rgRf', '$usuario', '$email', '$telefone', '$idPerfil', '$fiscal')";

            if (mysqli_query($con, $sql)) {
                $usuarioNovo = recuperaDados('usuarios', 'id', $con->insert_id);

                $mensagem = mensagem("success", "Usuário cadastrado com sucesso! Você está sendo redirecionado para a tela de login.");
                $alert = "<script>
                        Swal.fire({
                          title: 'Usuário Cadastrado',
                         html: '<b>Usuário:</b> {$usuarioNovo['usuario']} <br> <b>Senha Inicial:</b> siscontrat2019',
                          type: 'success',
                          allowOutsideClick: false,
                            allowEscapeKey: false,
                            showCancelButton: false,
                          confirmButtonText: 'Confirmar'
                        }).then(function() {
                          window.location.href = 'index.php';
                        });
                    </script>";

            } else {
                $mensagem = mensagem("danger", "Erro no cadastro de usuário! Tente novamente.");
            }
        }
    } else {
        $mensagem = mensagem("danger", "Código inválido!");
    }
}
?>
<html ng-app="sisContrat">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SisContrat | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="visual/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="visual/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="visual/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="visual/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="visual/plugins/iCheck/square/blue.css">
    <!-- Sweet Alert 2 -->
    <script src="visual/plugins/sweetalert2/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="visual/plugins/sweetalert2/sweetalert2.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="content-wrapper content hold-transition">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper content">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Usuário</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usuários</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="cadastro_usuario.php" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nome">Nome Completo* </label>
                                    <input type="text" id="nome" name="nome" class="form-control" required>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="tipo">É estagiário/jovem monitor? * </label> <br>
                                    <label><input type="radio" name="jovem_monitor" id="jovem_monitor" value="1" required> Sim
                                    </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="jovem_monitor" id="jovem_monitor" value="0"> Não
                                    </label>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="rf_usuario">RF/RG* </label>
                                    <input type="text" id="rgrf_usuario" name="rgrf_usuario" class="form-control"
                                           disabled required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="rf_usuario">Usuário* </label>
                                    <div id='resposta'></div>
                                    <input type="text" id="usuario" name="usuario" class="form-control" maxlength="7"
                                           required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4" id="divEmail">
                                    <label for="email">E-mail* </label>
                                    <input type="email" id="email" name="email" class="form-control" maxlength="100"
                                           required>
                                    <span class="help-block" id="spanHelp"></span>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="tel_usuario">Telefone* </label>
                                    <input type="text" id="tel_usuario" name="tel_usuario"
                                           class="form-control" onkeyup="mascara( this, mtel );" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="perfil">Código* </label> <br>
                                    <input type="text" name="perfil" id="perfil" class="form-control"
                                           maxlength="9" minlength="9" required >
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Senha inicial:</label> <br>siscontrat2019
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="index.php" class="btn btn-default pull-left">
                                Voltar a tela de Login
                            </a>
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
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
<!-- jQuery 3 -->
<script src="visual/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="visual/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="visual/plugins/iCheck/icheck.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="visual/dist/js/scripts.js"></script>
<script>

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

        let jovemMonitor = document.getElementsByName("jovem_monitor");

        for (i = 0; i < jovemMonitor.length; i++) {
            if (jovemMonitor[i].checked) {
                let escolhido = jovemMonitor[i].value;

                if (escolhido == 1) {
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').unmask();
                    $('#rgrf_usuario').attr('maxlength', '');
                    $('#rgrf_usuario').keypress(function (event) {
                        geraUsuarioRg();
                    });
                    $('#rgrf_usuario').blur(function (event) {
                        geraUsuarioRg();
                    });

                } else if (escolhido == 0) {
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').mask('000.000.0');
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

    const url = `<?=$url?>`;

    var email = $("#email");

    // adiciona o evento de onblur no campo de email
    email.blur(function () {
        $.ajax({
            url: url,
            type: 'POST',
            data: {"email": email.val()},

            success: function (data) {

                let divEmail = document.querySelector('#divEmail');

                // verifica se o que esta sendo retornado é 1 ou 0
                if (data.ok) {
                    divEmail.classList.remove("has-error");
                    document.getElementById("spanHelp").innerHTML = '';
                    $('#cadastra').attr('disabled', false);
                } else {
                    divEmail.classList.add("has-error");
                    document.getElementById("spanHelp").innerHTML = "Email em uso!";
                    $('#cadastra').attr('disabled', true);
                }
            }
        });
    });
</script>
<?php echo isset($alert) ? $alert : "" ?>
</body>
</html>