<?php

include "includes/menu_interno.php";
$con = bancoMysqli();

if(isset($_POST['tipo_representante'])){
    $tipo_representante = $_POST['tipo_representante'];

    $_SESSION['tipo_representante'] = $_POST['tipo_representante'];
}

if (isset($_POST['pesquisa'])) {
    $cpf = $_POST['cpf'];

    $representante_legal = recuperaDados("representante_legais", "cpf", $cpf);

    if ($representante_legal == NULL) {
        $_SESSION['cpfRepresentante'] = $cpf;
        $mensagem = mensagem("info", "Representante ainda não cadastrado");
        $cadastrar = true;
    } else {
        $mensagem = mensagem("info", $representante_legal['nome']. " já cadastrado");
        $_SESSION['idRepresentante'] = $representante_legal['id'];
        $cadastrar = false;
    }
}

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Pesquisar Representante</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">

                    <div align="center">
                        <h3 class="box-title"><?php if (isset($mensagem)) {
                                echo $mensagem;
                            }; ?></h3>
                    </div>

                    <div class="box-body row">
                        <?php
                        if (isset($cadastrar)) {
                        if ($cadastrar == true) {
                            ?>
                            <div class="form-group col-md-3 col-md-offset-4">
                                <form method="POST" action="?perfil=evento&p=representante_cadastro" role="form">
                                    <button type="submit" name="pesquisar_pessoa_juridica"
                                            class="btn btn-block btn-primary btn-lg"> Cadastrar
                                    </button>
                                </form>
                            </div>

                            <?php
                        } else {
                        ?>
                        <div class="form-group col-md-3 col-md-offset-4">
                            <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                                <button type="submit" name="inserir"
                                        class="btn btn-block btn-primary btn-lg"> Inserir
                                </button>
                            </form>
                            <?php
                            }
                            }
                            ?>
                        </div>
                        <form method="POST" action="?perfil=evento&p=representante_busca" role="form">
                            <div class="box-body">

                                <div class="form-group col-md-offset-4 col-md-3">
                                    <h2 for="cpf">CPF:</h2>
                                    <div class="row">
                                        <input type="text" name="cpf" id="cpf" class="form-control">

                                        <button type="submit" name="pesquisa" id="pesquisa"
                                                class="btn btn-info pull-right">
                                            Pesquisa
                                        </button>

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

    </section>
</div>