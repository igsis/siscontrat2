<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>EMIA - Dados para contratação</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Cadastro de dados para contratação</h4>
            </div>
            <div class="box-body">
                <form action="?perfil=emia&p=dados_contratacao&sp=listagem" method="POST" role="form">
                    <div class="row">
                        <div class="col-md-6">
                        <label for="pf">Pessoa Física: *</label>
                        <select name="pf" id="pf" class="form-control" required value="">
                            <option value="">Selecione uma pessoa física...</option>
                            <?php
                                 geraOpcao('pessoa_fisicas');
                            ?>
                        </select>
                    </div>

                        <div class="col-md-6">
                            <label for="ano">Ano: *</label>
                            <input name="ano" id="ano" type="number" min="2018" required class="form-control">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="local">Local: *</label>
                            <select name="local" id="local" required class="form-control" value="">
                                <option value="">Selecione um local...</option>
                                <?php
                                    geraOpcao('locais');
                                ?>
                            </select>
                        </div>

                            <div class="col-md-4">
                                <label for="cargo">Cargo: *</label>
                                <select name="cargo" id="cargo" class="form-control" required value="">
                                    <option value="">Selecione um cargo...</option>
                                    <?php
                                        geraOpcao('emia_cargos');
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="vigencia">Vigência: *</label>
                                <select name="vigencia" id="vigencia" class="form-control" required value="">
                                    <option value="">Selecione a vigência...</option>
                                    <?php
                                        geraOpcao('emia_vigencias');
                                    ?>
                                </select>
                            </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="form-group col-md-4 pull-right" id="msgEscondeAno">
                            <span style="color: red;"><b>Ano escolhido é maior que a vigência!</b></span>
                        </div>
                    </div>

                    <div class="row">
                            <div class="col-md-12">
                                <label for="cronograma">Cronograma: </label>
                                <textarea name="cronograma" id="cronograma"  rows="3" type="text" class="form-control"> </textarea>
                            </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao"  rows="3" type="text" class="form-control"> </textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control">
                                <option value="">Selecione um fiscal...</option>
                                <?php
                                    geraOpcaoUsuario("usuarios", 1, "");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
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
                <a href="?perfil=emia&p=dados_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <button type="submit" class="btn btn-primary pull-right" name="cadastrar" id="cadastrar">Cadastrar</button>
            </div>
            </form>
        </div>
    </section>
</div>

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
