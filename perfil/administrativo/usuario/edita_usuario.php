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
                    <form method="POST" action="?perfil=administrativo&p=pesquisa&sp=pesquisa_usuario" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nome">Nome Completo *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="rf_usuario">RG</label>
                                    <input type="text" id="rg_usuario" name="rg_usuario" class="form-control" minlength="12" onkeypress="geraUsuarioRg()" onblur="geraUsuarioRg()">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="rf_usuario">RF</label>
                                    <input data-mask="000.000.0" type="text" id="rf_usuario" name="rf_usuario" class="form-control" minlength="9" onkeypress="geraUsuarioRf()" onblur="geraUsuarioRf()">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="tel_usuario">Telefone *</label>
                                    <input data-mask="(00) 0000-00000" type="text" id="tel_usuario" name="tel_usuario" class="form-control" maxlength="100" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="email">E-mail *</label>
                                    <input type="email" id="email" name="email" class="form-control" maxlength="100" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="rf_usuario">Usuário *</label>
                                    <input type="text" id="usuario" name="usuario" class="form-control" maxlength="7" required readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nivel_acesso">Nível de acesso </label> <br>
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
                            <button type="submit" name="cadastra" class="btn btn-primary pull-right">Cadastrar</button>
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

    $('#num_processo').mask('0000.0000/0000000-0', {reverse: true});


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
        let usuarioRf = document.querySelector("#rf_usuario").value;

        if(usuarioRf != ''){
            $("#rg_usuario").attr('disabled', true);
            $("#rg_usuario").attr('required', false);
        }

        if(usuarioRf == ''){
            $("#rg_usuario").attr('disabled', false);
            $("#rg_usuario").attr('required', true);
        }

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
        let usuarioRg = document.querySelector("#rg_usuario").value;

        if(usuarioRg != ''){
            $("#rf_usuario").attr('disabled', true);
            $("#rf_usuario").attr('required', false);
        }else{
            $("#rf_usuario").attr('disabled', false);
            $("#rf_usuario").attr('required', true);
        }

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

</script>
