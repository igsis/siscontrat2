<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
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
                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="original">É um evento original?</label> <br>
                                    <label><input type="radio" name="original" value="1" checked> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="original" value="0"> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contratacao">Haverá contratação?</label> <br>
                                    <label><input type="radio" name="contratacao" value="1" checked> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="contratacao" value="0"> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contratacao">Espaço em que será realizado o evento é público?</label> <br>
                                    <label><input type="radio" name="tipoLugar" value="1"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipoLugar" value="0" checked> Não </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="tipo">Este evento é cinema?</label> <br>
                                    <label><input type="radio" name="tipo" value="2"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipo" value="1" checked> Não </label>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fomento">É fomento/programa?</label> <br>
                                    <label><input type="radio" class="fomento" name="fomento" value="1" id="sim"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" class="fomento" name="fomento" value="0" id="nao" checked> Não </label>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="tipoFomento">Fomento/Programa</label> <br>
                                    <select class="form-control" name="tipoFomento" id="tipoFomento">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("fomentos");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nomeEvento">Nome do evento *</label>
                                <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                       placeholder="Digite o nome do evento" maxlength="240" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="relacaoJuridica">Tipo de relação jurídica *</label>
                                    <select class="form-control" name="relacaoJuridica" id="relacaoJuridica" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("relacao_juridicas");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="projetoEspecial">Projeto Especial *</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="acao">Ações (Expressões Artístico-culturais) * <i>(multipla escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalAcoes' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                   <?php
                                        geraCheckboxEvento('acoes', 'acao', 'acao_evento');
                                    ?>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="acao">Público (Representatividade e Visibilidade Sócio-cultural)* <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalPublico' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <?php
                                        geraCheckboxEvento('publicos', 'publico', 'evento_publico');
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sinopse">Sinopse *</label><br/>
                                <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i></p>
                                <textarea name="sinopse" id="sinopse" class="form-control" rows="5" required></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="fiscal">Fiscal *</label>
                                    <select class="form-control" id="fiscal" name="fiscal" required>
                                        <option value="">Selecione um fiscal...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, "");
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="suplente">Suplente *</label>
                                    <select class="form-control" id="suplente" name="suplente" required>
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalAcoes" role="dialog" aria-labelledby="lblmodalAcoes" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ações (Expressões Artístico-culturais)</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Ação</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlConsultaAcoes = "SELECT acao, descricao FROM acoes WHERE publicado = '1' ORDER BY 1";
                    foreach ($con->query($sqlConsultaAcoes)->fetch_all(MYSQLI_ASSOC) as $acao) {
                        ?>
                        <tr>
                            <td><?= $acao['acao'] ?></td>
                            <td><?= $acao['descricao'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPublico" role="dialog" aria-labelledby="lblmodalPublico" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Público (Representatividade e Visibilidade Sócio-cultural)</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Público</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlConsultaPublico = "SELECT publico, descricao FROM publicos WHERE publicado = '1' ORDER BY 1";
                    foreach ($con->query($sqlConsultaPublico)->fetch_all(MYSQLI_ASSOC) as $publico) {
                        ?>
                        <tr>
                            <td><?= $publico['publico'] ?></td>
                            <td><?= $publico['descricao'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var fomento = $('.fomento');
    fomento.on("change", verificaFomento);
    $(document).ready(verificaFomento());

    function verificaFomento () {
        if ($('#sim').is(':checked')) {
            $('#tipoFomento')
                .attr('disabled', false)
                .attr('required',true)
        } else {
            $('#tipoFomento')
                .attr('disabled', true)
                .attr('required',false)
        }
    }
</script>