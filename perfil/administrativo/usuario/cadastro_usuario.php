<?php
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/siscontrat2/funcoes/api_verifica_email.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Usuário</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usuários</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=usuario&sp=edita_usuario" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nome">Nome Completo *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="tipo">É jovem monitor?</label> <br>
                                    <label><input type="radio" name="jovem_monitor" id="jovem_monitor" value="1"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="jovem_monitor" id="jovem_monitor" value="0"> Não </label>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="rf_usuario">RF/RG</label>
                                    <input type="text" id="rgrf_usuario" name="rgrf_usuario" class="form-control" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="rf_usuario">Usuário *</label>
                                    <div id='resposta'></div>
                                    <input type="text" id="usuario" name="usuario" class="form-control" maxlength="7" required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="email">E-mail *</label>
                                    <input type="email" id="email" name="email" class="form-control" maxlength="100" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="tel_usuario">Telefone *</label>
                                    <input data-mask="(00) 0000-00000" type="text" id="tel_usuario" name="tel_usuario" class="form-control" maxlength="100" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="perfil">Perfil </label> <br>
                                    <select class="form-control" id="perfil" name="perfil">
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("perfis");
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">Cadastrar</button>
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

<script>
    function habilitaCampo(id) {
        if(document.getElementById(id).disabled==true){document.getElementById(id).disabled=false}
    }

    function desabilitarCampo(id){
        if(document.getElementById(id).disabled==false){document.getElementById(id).disabled=true}
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

        for (var i = 0; i < jovemMonitor.length; i++){
            if(jovemMonitor[i].checked){
                var escolhido = jovemMonitor[i].value;

                if(escolhido == 1){
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').keypress(function(event) {
                        geraUsuarioRg();
                    });
                    $('#rgrf_usuario').blur(function(event) {
                        geraUsuarioRg();
                    });

                } else if(escolhido == 0){
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').mask('000.000.0');
                    $('#rgrf_usuario').keypress(function(event) {
                        geraUsuarioRf();
                    });
                    $('#rgrf_usuario').blur(function(event) {
                        geraUsuarioRf();
                    });
                }
            }
        }
    })

    const url = `<?=$url?>`;

    var email = $("#email");
    email.blur(function() {
        $.ajax({
            url: url,
            type: 'POST',
            data:{"email" : email.val()},
            success: function(data) {
                console.log(data);
                data = $.parseJSON(data);
                $("#resposta").text(data.email);
            }
        });
    });
</script>
