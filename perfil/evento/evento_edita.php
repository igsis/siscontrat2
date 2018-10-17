<?php
$con = bancoMysqli();
include "includes/menu_principal.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $nomeEvento = $_POST['nomeEvento'];
    $relacaoJuridica = $_POST['relacaoJuridica'];
    $projetoEspecial = $_POST['projetoEspecial'];
    $sinopse = $_POST['sinopse'];
    $tipo = $_POST['tipo'];
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $original = $_POST['originall'];
    $contratacao = $_POST['contratacao'];
    $eventoStatus = $_POST['eventoStatus'];
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO eventos (nome_evento,
                                 relacao_juridica_id, 
                                 projeto_especial_id, 
                                 tipo, 
                                 sinopse, 
                                 fiscal_id, 
                                 suplente_id, 
                                 usuario_id, 
                                 contratacao, 
                                 original, 
                                 evento_status_id) 
                          VALUES ('$nomeEvento',
                                  '$relacaoJuridica',
                                  '$projetoEspecial',
                                  '$tipo',
                                  '$sinopse',
                                  '$fiscal',
                                  '$suplente',
                                  '$usuario',
                                  '$contratacao',
                                  '$original',
                                  '$eventoStatus')";

    if(mysqli_query($con, $sql))
    {
        $idEvento = recuperaUltimo("eventos");
        $mensagem = "
            <div class=\"col-md-12\">
                <div class=\"box box-success box-solid\">
                    <div class=\"box-header with-border\">
                        <h3 class=\"box-title\">Cadastrado com sucesso</h3>
                        <div class=\"box-tools pull-right\">
                            <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>
                        </div>
                    </div>
                </div>
            </div>";
        //gravarLog($sql_insere);
    }else{
         $mensagem = "
            <div class=\"col-md-12\">
                <div class=\"box box-danger box-solid\">
                    <div class=\"box-header with-border\">
                        <h3 class=\"box-title\">Erro ao gravar! Tente novamente</h3>
                        <div class=\"box-tools pull-right\">
                            <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>
                        </div>
                    </div>
                </div>
            </div>";
    }
}

$evento = recuperaDados("eventos","id",$idEvento);
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Gerais</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>

                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nomeEvento">Nome do evento</label>
                                <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                       placeholder="Digite o nome do evento" maxlength="240">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Tipo de relação jurídica</label>
                                    <select class="form-control" name="relacaoJuridica">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("relacao_juridicas", "");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Projeto Especial</label>
                                    <select class="form-control" name="projetoEspecial">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sinopse">Sinopse</label>
                                <textarea name="sinopse" id="sinopse" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-4">
                                    <label for="tipoEvento">Tipo do Evento</label>
                                    <select class="form-control" name="tipo">
                                        <option value="">Selecione uma opção...</option>
                                        <option value="1">Atração</option>
                                        <option value="2">Oficina</option>
                                        <option value="3">Filme</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Fiscal</label>
                                    <select class="form-control" name="fiscal">
                                        <option value="">Selecione um fiscal...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, "");
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Suplente</label>
                                    <select class="form-control" name="suplente">
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label for="original">É um evento original?</label> <br>
                                    <label><input type="radio" name="original" value="1" checked> Sim </label>
                                    <label><input type="radio" name="original" value="0"> Não </label>
                                </div>

                                <div class="form-group">
                                    <label for="original">É contratado?</label> <br>
                                    <label><input type="radio" name="contratacao" value="1" checked> Sim </label>
                                    <label><input type="radio" name="contratacao" value="0"> Não </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Status do Evento</label>
                                <select class="form-control" name="eventoStatus">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao("evento_status", "");
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
