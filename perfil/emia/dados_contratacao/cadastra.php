<?php
$con = bancoMysqli();
?>

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
                <form action="?perfil=emia&p=dados_contratacao&sp=edita" method="POST" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pf">Pessoa Física: *</label>
                            <select name="pf" id="pf" class="form-control select2bs4" required>
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
                            <select name="local" id="local" required class="form-control select2bs4">
                                <option value="">Selecione um local...</option>
                                <?php
                                geraOpcaoPublicado('locais');
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <select name="cargo" id="cargo" class="form-control" required value="">
                                <option value="">Selecione um cargo...</option>
                                <?php
                                geraOpcaoPublicado('emia_cargos');
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="vigencia">Vigência: *</label>
                            <select class="form-control" name="vigencia" id="vigencia" required>
                                <option value="">Selecione a vigência...</option>
                                <?php
                                $opcoesVigencia = $con->query("SELECT id, ano, descricao FROM emia_vigencias WHERE publicado = 1");
                                if ($opcoesVigencia->num_rows > 0) {
                                    while ($opcoesArray = mysqli_fetch_array($opcoesVigencia)) { ?>
                                        <option value="<?= $opcoesArray['id'] ?>"> <?= $opcoesArray['ano'] . " (" . $opcoesArray['descricao'] . ")" ?> </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span id="msgEscondeAno" class="pull-right" style="color: red;"><b>Ano escolhido é maior que a vigência!</b></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="cronograma">Cronograma: </label>
                            <textarea name="cronograma" id="cronograma" rows="3" type="text"
                                      class="form-control"> </textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3" type="text"
                                      class="form-control"> </textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control select2bs4">
                                <option value="">Selecione um fiscal...</option>
                                <?php
                                geraOpcaoUsuario("usuarios", 1, "8");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
                            <select name="suplente" id="suplente" class="form-control select2bs4">
                                <option value="">Selecione um suplente...</option>
                                <?php
                                geraOpcaoUsuario("usuarios", 1, "11");
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=emia&p=dados_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <button type="submit" class="btn btn-primary pull-right" name="cadastrar" id="cadastrar">Cadastrar
                </button>
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
        let valorVigencia = $('#vigencia option:selected').text();
        valorVigencia = parseInt(valorVigencia.substring(0, 5))
        if (ano.val() > valorVigencia) {
            botao.prop('disabled', true);
            isMsgAno.show();
        } else {
            botao.prop('disabled', false);
            isMsgAno.hide();
        }
    }

    ano.on('change', maior);
    vigencia.on('change', maior);

    $(document).ready(maior);
</script>


