<?php

include "includes/menu_interno.php";
$con = bancoMysqli();

if (isset($_POST['pesquisa'])) {
    $cnpj = $_POST['cnpj'];

    $pessoa_juridica = recuperaDados("pessoa_juridicas", "cnpj", $cnpj);

    if ($pessoa_juridica == NULL) {
        $_SESSION['cnpj'] = $cnpj;
        $mensagem = mensagem("info", "Pessoa jurídica não cadastrada");
        $cadastrar = true;
    } else {
        $mensagem = mensagem("info", $pessoa_juridica['razao_social']. " já cadastrada");
        $cadastrar = false;
    }
}

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Pesquisar Pessoa Jurídica</h2>

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
                                <form method="POST" action="?perfil=evento/pessoa_juridica/representante_cadastro" role="form">
                                    <button type="submit" name="pesquisar_pessoa_juridica"
                                            class="btn btn-block btn-primary btn-lg">Cadastrar Representante
                                    </button>
                                </form>
                            </div>

                            <?php
                        } else {
                        ?>
                        <div class="form-group col-md-3 col-md-offset-4">
                            <form method="POST" action="?perfil=evento/pessoa_juridica/pj_edita" role="form">
                                <input type="hidden" name="idPessoaJuridica" id="idPessoaJuridica" value="<?= $pessoa_juridica['id']; ?>">
                                <button type="submit" name="carregar"
                                        class="btn btn-block btn-primary btn-lg"> Inserir
                                </button>
                            </form>
                            <?php
                            }
                            }
                            ?>
                        </div>
                        <form method="POST" action="?perfil=evento/pessoa_juridica/pj_pesquisa" role="form">
                            <div class="box-body">

                                <div class="form-group col-md-offset-4 col-md-3">
                                    <h2 for="cnpj">CNPJ:</h2>
                                    <div class="row">
                                        <input type="text" name="cnpj" id="cnpj" class="form-control">

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