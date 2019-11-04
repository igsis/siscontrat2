<?php

$con = bancoMysqli();

$idEc = $_SESSION['idEC'];

$sql = "SELECT pf.nome,
		       ec.ano,
               l.local,
               c.cargo,
               v.ano AS 'vigencia',
               v.id,
               ec.cronograma,
               ec.observacao,
               v.numero_parcelas,
               f.nome_completo AS 'fiscal',
               s.nome_completo AS 'suplente'
		FROM emia_contratacao AS ec
        INNER JOIN pessoa_fisicas AS pf ON ec.pessoa_fisica_id = pf.id
        INNER JOIN locais AS l ON l.id = ec.local_id
        INNER JOIN emia_cargos AS c ON c.id = ec.emia_cargo_id
        INNER JOIN emia_vigencias AS v ON v.id = ec.emia_vigencia_id
        INNER JOIN usuarios AS f ON ec.fiscal_id = f.id
		INNER JOIN usuarios AS s ON ec.suplente_id = s.id
        WHERE ec.publicado = 1 AND ec.id = '$idEc'";
$ec = $con->query($sql)->fetch_array();

$valor = 00.0;

$idVigencia = $ec['id'];
$sql = "SELECT valor FROM emia_parcelas WHERE emia_vigencia_id = '$idVigencia' AND publicado = 1 AND valor <> 0.00";
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
            <form method="post" action="?perfil=emia&p=pedido_contratacao&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pf">Pessoa Física: *</label>
                            <select name="pf" id="pf" class="form-control" required disabled>
                                <option><?= $ec['nome'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="ano">Ano: *</label>
                            <input name="ano" id="ano" type="number" required class="form-control"
                                   value="<?= $ec['ano'] ?>" disabled>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="local">Local: *</label>
                            <select name="local" id="local" required class="form-control" disabled>
                                <option><?= $ec['local'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <select name="cargo" id="cargo" class="form-control" required disabled>
                                <option><?= $ec['cargo'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="vigencia">Vigência: *</label>
                            <select name="vigencia" id="vigencia" class="form-control" required disabled>
                                <option><?= $ec['vigencia'] ?></option>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="cronograma">Cronograma: </label>
                            <textarea name="cronograma" id="cronograma" rows="3" type="text" class="form-control"
                                      disabled><?= $ec['cronograma'] ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3" type="text" class="form-control"
                                      disabled><?= $ec['observacao'] ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control" disabled>
                                <option><?= $ec['fiscal'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
                            <select name="suplente" id="suplente" class="form-control" disabled>
                                <option><?= $ec['suplente'] ?></option>
                            </select>
                        </div>
                    </div>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="verba">Verba: *</label>
                                <select name="verba" id="verba" class="form-control">
                                    <option value="">Selecione uma verba...</option>
                                    <?php
                                    geraOpcao('verbas');
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="numParcelas">Número de parcelas:</label>
                                <input type="text" name="numParcelas" value="<?= $ec['numero_parcelas'] ?>" readonly
                                       class="form-control" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="valor">Valor Total:</label>
                                <input type="text" name="valor" onKeyPress="return(moeda(this,'.',',',event))"
                                       class="form-control" value="<?= $valor ?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="dataKit">Data kit pagamento:</label>
                                <input type="date" name="dataKit" class="form-control" id="datepicker10"
                                       placeholder="DD/MM/AAAA">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="numeroProcesso">Número do Processo: *</label>
                                <input type="text" name="numeroProcesso" id="numProcesso" class="form-control"
                                       data-mask="9999.9999/9999999-9" minlength="19">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="forma_pagamento">Forma de pagamento: </label>
                                <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                          rows="8"></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="justificativa">Justificativa: </label>
                                <textarea id="justificativa" name="justificativa" class="form-control"
                                          rows="8"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="justificativa">Observação: </label>
                                <textarea id="observacao" name="observacao" class="form-control"
                                          rows="8"></textarea>
                            </div>
                        </div>
                    </div>


                <div class="box-footer">
                    <input type="hidden" name="idEc" value="<?= $idEc ?>" id="idPc">
                    <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>