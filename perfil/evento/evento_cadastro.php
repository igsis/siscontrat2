<?php
    $con = bancoMysqli();
    include "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Gerais</h3>
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
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
