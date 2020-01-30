<?php

$con = bancoMysqli();

if (isset($_POST['carregar']))
    $idPc = $_POST['idPc'];
else
    $idPc = $_SESSION['idPc'];

$_SESSION['idPc'] = $idPc;
$idPc = $_SESSION['idPc'];

$fc = recuperaDados('formacao_contratacoes', 'id', $idPc);
$pessoa_fisica = recuperaDados('pessoa_fisicas', 'id', $fc['pessoa_fisica_id'])['nome'];
$classificacao = recuperaDados('classificacao_indicativas', 'id', $fc['classificacao'])['classificacao_indicativa'];
$territorio = recuperaDados('territorios', 'id', $fc['territorio_id'])['territorio'];
$coordenadoria = recuperaDados('coordenadorias', 'id', $fc['coordenadoria_id'])['coordenadoria'];
$subprefeitura = recuperaDados('subprefeituras', 'id', $fc['subprefeitura_id'])['subprefeitura'];
$programa = recuperaDados('programas', 'id', $fc['programa_id'])['programa'];
$linguagem = recuperaDados('linguagens', 'id', $fc['linguagem_id'])['linguagem'];
$projeto = recuperaDados('projetos', 'id', $fc['projeto_id'])['projeto'];
$cargo = recuperaDados('formacao_cargos', 'id', $fc['form_cargo_id'])['cargo'];
$vigencia = recuperaDados('formacao_vigencias', 'id', $fc['form_vigencia_id']);
$numParcelas = $vigencia['numero_parcelas'];
$fiscal = recuperaDados('usuarios', 'id', $fc['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $fc['suplente_id'])['nome_completo'];

$valor = 00.0;

$idVigencia = $vigencia['id'];
$sql = "SELECT valor FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia' AND publicado = 1 AND valor <> 0.00";
$query = mysqli_query($con, $sql);

while ($count = mysqli_fetch_array($query))
    $valor += $count['valor'];

$valor = dinheiroParaBr($valor);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Criar Pedido de Contratação</h2>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Dados para Contratação</h4>
            </div>
            <div class="row" align="center">
                <?php
                if (isset($mensagem)) {
                    echo $mensagem;
                }
                ?>
            </div>
            <form method="post" action="?perfil=formacao&p=pedido_contratacao&sp=edita" role="form" id="formulario">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="ano">Ano: *</label>
                            <input type="number" min="2018" id="ano" name="ano" required class="form-control"
                                   value="<?= $fc['ano'] ?>" disabled>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="chamado">Chamado: *</label>
                            <input type="number" min="0" max="127" id="chamado" name="chamado"
                                   value="<?= $fc['chamado'] ?>" required class="form-control" disabled>
                        </div>

                    </div>

                    <div class="row">
                        <div class="from-group col-md-12">
                            <label for="pf">Pessoa Física: *</label>
                            <input type="text" class="form-control" required name="pessoa_fisica" id="pessoa_fisica"
                                   value="<?= $pessoa_fisica ?>" disabled>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa *</label>
                            <input type="text" name="classificacao" required value="<?= $classificacao ?>" disabled
                                   class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="territorio">Território *</label>
                            <input type="text" name="territorio" required value="<?= $territorio ?>" disabled
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="coordenadoria">Coordenadoria *</label>
                            <input type="text" name="coordenadoria" required value="<?= $coordenadoria ?>" disabled
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="subprefeitura">Subprefeitura *</label>
                            <input type="text" name="subprefeitura" required value="<?= $subprefeitura ?>" disabled
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="programa">Programa *</label>
                            <input type="text" name="programa" required value="<?= $programa ?>" disabled class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="linguagem">Linguagem *</label>
                            <input type="text" name="linguagem" required value="<?= $linguagem ?>" disabled class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="projeto">Projeto *</label>
                            <input type="text" name="projeto" required value="<?= $projeto ?>" disabled class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cargo">Cargo *</label>
                            <input type="text" name="cargo" required value="<?= $cargo ?>" disabled class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="vigencia">Vigência *</label>
                            <input type="text" name="vigencia" required value="<?= $vigencia['ano'] ?>" disabled
                                   class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3"
                                      class="form-control" required disabled><?= $fc['observacao'] ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fiscal">Fiscal *</label>
                            <input type="text" name="fiscal" required value="<?= $fiscal ?>" disabled class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fiscal">Suplente </label>
                            <input type="text" name="suplente" required value="<?= $suplente ?>" disabled class="form-control">
                        </div>
                    </div>


                    <hr>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="verba">Verba* </label>
                            <select name="verba" id="verba" required class="form-control">
                                <?php geraOpcao('verbas'); ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="numParcelas">Número de parcelas</label>
                            <input type="text" name="numParcelas" required value="<?= $numParcelas ?>" readonly
                                   class="form-control" >
                        </div>

                        <div class="form-group col-md-3">
                            <label for="valor">Valor</label>
                            <input type="text" name="valor" onKeyPress="return(moeda(this,'.',',',event))"
                                   class="form-control" required value="<?= $valor ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="dataKit">Data kit pagamento: *</label>
                            <input type="date" name="dataKit" class="form-control" required id="datepicker10"
                                   placeholder="DD/MM/AAAA">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="numeroProcesso">Número do Processo: *</label>
                            <input type="text" name="numeroProcesso" id="numProcesso" required class="form-control"
                                   data-mask="9999.9999/9999999-9" minlength="19">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="processoMae">Número do Processo Mãe: *</label>
                            <input type="text" name="processoMae" id="processoMae" required class="form-control"
                                   data-mask="9999.9999/9999999-9" minlength="19">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="forma_pagamento">Forma de pagamento: *</label>
                            <textarea id="forma_pagamento" name="forma_pagamento" required class="form-control"
                                      rows="8"></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="justificativa">Justificativa: *</label>
                            <textarea id="justificativa" name="justificativa" required  class="form-control"
                                      rows="8" ></textarea>
                        </div>
                    </div>


                    <div class="row">
                        <?php
                        for ($i = 0; $i < 3; $i++) {
                            ?>
                            <div class="form-group col-md-4">
                                <label for="local[]">Local #<?= $i + 1 ?></label>
                                <select name="local[]" id="local[]" class="form-control">
                                    <option value="0">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao('locais');
                                    ?>
                                </select>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="row" id="msgEsconde" style="display: none;">
                        <div class="col-md-12">
                            <span style="color: red;"><b>Selecione ao menos um local!</b></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação </label>
                            <textarea id="observacao" name="observacao" class="form-control"
                                      rows="8"></textarea>
                        </div>
                    </div>


                    <div class="box-footer">
                        <input type="hidden" name="idPc" value="<?= $idPc ?>" id="idPc">

                        <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                            Cadastrar
                        </button>
                    </div>
            </form>
        </div>
    </section>
</div>

<script>
    let local = document.getElementsByName("local[]");
    const idLocal = "Selecione uma opção... ";
    const nenhumaOpcao = local[0];
    var isMsg = $('#msgEsconde');
    isMsg.hide();

    $('#formulario').submit(function (event) {
        let count = 0;

        for (let i = 0; i < local.length; i++) {
            if (local[i].value == 0)
                count++;
        }

        if (count == 3) {
            event.preventDefault()
            isMsg.show();
            nenhumaOpcao.focus()
        }
    })

</script>
