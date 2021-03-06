<?php

$con = bancoMysqli();

$idEc = $_POST['idDados'];

$sql = "SELECT pf.nome,
		       ec.ano,
               l.local,
               c.cargo,
               v.ano AS 'vigencia',
               v.descricao,
               v.id,
               ec.cronograma,
               ec.observacao,
               v.numero_parcelas,
               ec.pessoa_fisica_id,
               f.nome_completo AS 'fiscal',
               s.nome_completo AS 'suplente'
		FROM emia_contratacao AS ec
        INNER JOIN pessoa_fisicas AS pf ON ec.pessoa_fisica_id = pf.id
        INNER JOIN locais AS l ON l.id = ec.local_id
        INNER JOIN emia_cargos AS c ON c.id = ec.emia_cargo_id
        INNER JOIN emia_vigencias AS v ON v.id = ec.emia_vigencia_id
        LEFT JOIN usuarios AS f ON ec.fiscal_id = f.id
		LEFT JOIN usuarios AS s ON ec.suplente_id = s.id
        WHERE ec.publicado = 1 AND ec.id = '$idEc'";
$ec = $con->query($sql)->fetch_array();

$valor = 00.0;

$idVigencia = $ec['id'];
$sql = "SELECT valor FROM emia_parcelas WHERE emia_vigencia_id = '$idVigencia' AND publicado = 1 AND valor <> 0.00";
$query = mysqli_query($con, $sql);

while ($count = mysqli_fetch_array($query))
    $valor += $count['valor'];

$valor = dinheiroParaBr($valor);

$txtJustificativa = "A EMIA - Escola Municipal de Iniciação Artística existe desde 1980 e passou a ser organizada de acordo com a Lei 15.372 em 03 de maio de 2011. Atende em média 1000 (mil) alunos, entre 5 e 13 anos, em cursos regulares e integrados nas áreas de artes visuais, dança, música e teatro. As crianças ingressam com idade entre 5 e 7 anos, por meio de sorteio público, e podem permanecer por um período de seis a oito anos. Para suprir essa demanda de alunos (distribuídos entre os cursos regulares, e paralelamente, a partir dos 7 anos, com opção de participação em cursos Optativos, Corais, Orquestra e Oficinas,) a escola necessita de um corpo docente constituído por artistas oficineiros, profissionais com formação nas quatro áreas artísticas oferecidas pela escola: Teatro, Dança, Música e Artes Visuais (Artes Plásticas). Na EMIA, o oficineiro é também um artista dentro da linguagem que trabalha com a criança. O seu projeto de trabalho com o grupo de crianças está intimamente ligado com o que ele, naquele momento, acredita e deseja realizar artisticamente. Trata-se, portanto, de um ato criador que instigará o aprendizado da criança. Diante do Decreto nº 59.283 de 16/03/2020 e Portaria 31/SMC/2020, a EMIA tem mantido suas atividades de formação artístico-pedagógicas de forma remota, propondo atividades online, na forma de produção de conteúdo para os canais da escola e da supervisão de formação e também no formato de lives, onde são ministradas atividades ao vivo por meio de plataformas de teleconferência. Quando autorizadas pela municipalidade e pelas autoridades sanitárias, as atividades presenciais poderão ser retomadas paulatinamente."
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
                            <input type="hidden" name="pf" id="pf" value="<?=$ec['pessoa_fisica_id']?>">
                            <input type="text" value="<?=$ec['nome']?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="ano">Ano: *</label>
                            <input name="ano" id="ano" type="number" class="form-control"
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
                            <select name="cargo" id="cargo" class="form-control" disabled>
                                <option><?= $ec['cargo'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="vigencia">Vigência: *</label>
                            <select name="vigencia" id="vigencia" class="form-control" disabled>
                                <option><?= $ec['vigencia'] . " (" . $ec['descricao'] . ")" ?></option>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="cronograma">Cronograma: </label>
                            <textarea name="cronograma" id="cronograma" rows="3" type="text" class="form-control"
                                      disabled><?= checaCampo($ec['cronograma']) ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3" type="text" class="form-control"
                                      disabled><?= checaCampo($ec['observacao']) ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control" disabled>
                                <option><?= checaCampo($ec['fiscal']) ?></option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
                            <select name="suplente" id="suplente" class="form-control" disabled>
                                <option><?= checaCampo($ec['suplente']) ?></option>
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
                            <div class="form-group col-md-4">
                                <label for="dataKit">Data kit pagamento:</label>
                                <input type="date" name="dataKit" class="form-control" id="datepicker10"
                                       placeholder="DD/MM/AAAA" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="numeroProcesso">Número do Processo: *</label>
                                <input type="text" name="numeroProcesso" id="numProcesso" class="form-control"
                                       data-mask="9999.9999/9999999-9" minlength="19" required>
                            </div>

                            <div class="form-group col-md-4">
                            <label for="processoMae">Número do Processo Mãe: *</label>
                            <input type="text" name="processoMae" id="processoMae" required class="form-control"
                                   data-mask="9999.9999/9999999-9" minlength="19">
                        </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="forma_pagamento">Forma de pagamento: </label>
                                <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                          readonly
                                          placeholder="A FORMA DE PAGAMENTO É PREENCHIDA AUTOMATICAMENTE APÓS A CRIAÇÃO DO PEDIDO/EDIÇÃO DAS PARCELAS"
                                          rows="8"></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="justificativa">Justificativa: </label>
                                <textarea id="justificativa" name="justificativa" class="form-control"
                                          rows="8"><?= $txtJustificativa ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="observacao">Observação: </label>
                                <textarea id="observacao" name="observacao" class="form-control"
                                          rows="8"></textarea>
                            </div>
                        </div>
                    </div>


                <div class="box-footer">
                    <input type="hidden" name="idEc" value="<?= $idEc ?>">
                    <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>