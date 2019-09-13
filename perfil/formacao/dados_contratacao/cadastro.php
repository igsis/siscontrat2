<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Dados para Contratação</h2>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Dados para Contratação</h4>
            </div>

            <div class="box-body">
                <form method="POST" action="?perfil=formacao&p=dados_contratacao&sp=listagem" role="form">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="ano">Ano: *</label>
                            <input type="number" min="2018" id="ano" name="ano" required class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="chamado">Chamado: *</label>
                            <input type="number" min="0" max="127" id="chamado" name="chamado" max="127" required class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="from-group col-md-12">
                            <label for="pf">Pessoa Física: *</label>
                            <select required value="" name="idPF" id="idPF" class="form-control">
                                <option>Selecione a pessoa física...</option>
                                <?php
                                    geraOpcao('pessoa_fisicas');
                                ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa *</label>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-default"><i class="fa fa-info"></i></button>
                            <select required class="form-control" name="classificacao" id="classificacao">
                                <option value="">Selecione...</option>
                                <?php
                                    geraOpcao("classificacao_indicativas");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="territorio">Território *</label>
                            <select class="form-control" name="territorio" id="territorio" required>
                                <option value="">Selecione o território...</option>
                                <?php
                                    geraOpcao("territorios");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="coordenadoria">Coordenadoria *</label>
                            <select class="form-control" name="coordenadoria" id="coordenadoria" required>
                                <option value="">Selecione a coordenadoria...</option>
                                <?php
                                    geraOpcao("coordenadorias");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="subprefeitura">Subprefeitura *</label>
                            <select class="form-control" name="subprefeitura" id="subprefeitura" required>
                                <option value="">Selecione a subprefeitura...</option>
                                <?php
                                    geraOpcao("subprefeituras");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="programa">Programa *</label>
                            <select class="form-control" name="programa" id="programa" required>
                                <option value="">Selecione o programa...</option>
                                <?php
                                geraOpcao("programas");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="linguagem">Linguagem *</label>
                            <select class="form-control" name="linguagem" id="linguagem" required>
                                <option value="">Selecione a linguagem...</option>
                                <?php
                                    geraOpcao("linguagens");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="projeto">Projeto *</label>
                            <select class="form-control" name="projeto" id="projeto" required>
                                <option value="">Selecione o projeto...</option>
                                <?php
                                    geraOpcao("projetos");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cargo">Cargo *</label>
                            <select class="form-control" name="cargo" id="cargo" required>
                                <option value="">Selecione o cargo...</option>
                                <?php
                                geraOpcao("formacao_cargos");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="vigencia">Vigência *</label>
                            <select class="form-control" name="vigencia" id="vigencia" required>
                                <option value="">Selecione a vigência...</option>
                                <?php
                                geraOpcao("formacao_vigencias");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4" id="msgEscondeAno">
                            <span style="color: red;"><b>Ano escolhido é maior que a vigência!</b></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3" class="form-control"> </textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fiscal">Fiscal *</label>
                            <select name="fiscal" id="fiscal" class="form-control" required>
                                <option value="">Selecione um fiscal...</option>
                                <?php
                                    geraOpcaoUsuario("usuarios", 1, "");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fiscal">Suplente </label>
                            <select name="suplente" id="suplente" class="form-control">
                                <option value="">Selecione um suplente...</option>
                                <?php
                                    geraOpcaoUsuario("usuarios", 1, "");
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=formacao&p=dados_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                    Cadastrar
                </button>
            </div>
            </form>
        </div>
    </section>
</div>

    <?php @include "../perfil/includes/modal_classificacao.php"?>

    <script>
    let ano = $('#ano');
    let vigencia = $('#vigencia');
    let botao = $('#cadastra');
    var isMsgAno = $('#msgEscondeAno');
    isMsgAno.hide();

    function maior() {
        let valorvigencia = $('#vigencia option:selected');
        valorvigencia = parseInt(valorvigencia.text())
        if (ano.val() > valorvigencia) {
            botao.prop('disabled', true);
            isMsgAno.show();
        } else {
            botao.prop('disabled', false);
            isMsgAno.hide();
        }
    }

    ano.on('change', maior);
    vigencia.on('change', maior);

    $(document).ready(maior)
</script>


