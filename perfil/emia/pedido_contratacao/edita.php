<?php

$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idEc = $_POST['idEc'];
    $pf = $_POST['pf'];
    $num_processo = $_POST['numeroProcesso'];
    $verba = $_POST['verba'];
    $num_parcelas = $_POST['numParcelas'];
    $valor = dinheiroDeBr($_POST['valor']);
    $forma_pagamento = addslashes($_POST['forma_pagamento']) ?? null;
    $justificativa = addslashes($_POST['justificativa']) ?? null;
    $obs = addslashes($_POST['observacao']) ?? null;
    $processoMae = $_POST['processoMae'];
    $data_kit = $_POST['dataKit'];
}

if (isset($_POST['cadastra'])) {
    $sql = "INSERT INTO pedidos (origem_tipo_id, 
                                 origem_id, 
                                 pessoa_tipo_id,  
                                 pessoa_fisica_id, 
                                 numero_processo,
                                 numero_processo_mae, 
                                 verba_id, 
                                 numero_parcelas, 
                                 valor_total, 
                                 forma_pagamento, 
                                 data_kit_pagamento, 
                                 justificativa, 
                                 status_pedido_id, 
                                 observacao)
            VALUES('3',
                   '$idEc',
                   '1',
                   '$pf',
                   '$num_processo',
                   '$processoMae',
                   '$verba',
                   '$num_parcelas',
                   '$valor',
                   '$forma_pagamento',
                   '$data_kit',
                   '$justificativa',
                   '2',
                   '$obs')";
    if (mysqli_query($con, $sql)) {
        $idPedido = recuperaUltimo('pedidos');
        gravarLog($sql);
        $sqlInsert = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) 
                    SELECT p.id, ep.numero_parcelas, ep.valor, ep.data_pagamento 
                    FROM emia_parcelas ep 
                    INNER JOIN emia_contratacao ec ON ec.emia_vigencia_id = ep.emia_vigencia_id 
                    INNER JOIN pedidos p ON p.origem_id = ec.id 
                    WHERE p.origem_tipo_id = 3 AND p.id = '$idPedido'";
        mysqli_query($con, $sqlInsert);
        gravarLog($sqlInsert);

        $formaCompleta = "";

        $consultaParcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = $idPedido AND publicado = 1 ORDER BY numero_parcelas");

        $countForma = 0;

        while ($parcelasArray = mysqli_fetch_array($consultaParcelas)) {
            $forma = $countForma + 1 . "º parcela R$ " . dinheiroParaBr($parcelasArray['valor']) . ". Entrega de documentos a partir de " . exibirDataBr($parcelasArray['data_pagamento']) . ".\n";
            $formaCompleta = $formaCompleta . $forma;

            $countForma += 1;
        }
        $formaCompleta = $formaCompleta . "\nA liquidação de cada parcela se dará em 3 (três) dias úteis após a data de confirmação da correta execução do(s) serviço(s).";

        $sqlForma = "UPDATE pedidos SET forma_pagamento = '$formaCompleta' WHERE id = $idPedido AND origem_tipo_id = 3";
        mysqli_query($con, $sqlForma);

        $mensagem = mensagem("success", "Pedido de contratação cadastrado com sucesso.");

        $sqlUpdate = "UPDATE emia_contratacao SET pedido_id = '$idPedido' WHERE id = '$idEc'";
        mysqli_query($con, $sqlUpdate);
    } else {
        $mensagem = mensagem("danger", "Erro ao Cadastrar! Tente novamente.");
    }
}

if (isset($_POST['edita'])) {
    $idPedido = $_POST['idEc'];

    $sql = "UPDATE pedidos SET verba_id = '$verba', valor_total = '$valor', data_kit_pagamento = '$data_kit', numero_processo = '$num_processo', numero_processo_mae = '$processoMae',forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$obs', numero_parcelas = '$num_parcelas' WHERE id = '$idPedido'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);
        $mensagem = mensagem("success", "Pedido de contratação salvo com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Erro ao Salvar! Tente novamente.");
    }

}


if (isset($_POST['carregar'])) {
    $idPedido = $_POST['idEc'];
}

if (isset($_POST['parcelaEditada'])) {
    $idPedido = $_POST['idPedido'];
    $numParcelas = $_POST['numParcelas'];

    $parcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1")->fetch_all(MYSQLI_ASSOC);

    if (count($parcelas) > 0) {
        foreach ($parcelas as $parcela) {
            $sqlDeletaParcela = "UPDATE parcelas SET publicado = '0' WHERE pedido_id = '$idPedido' AND numero_parcelas = '{$parcela['numero_parcelas']}'";
            if ($con->query($sqlDeletaParcela)) {
                gravarLog($sqlDeletaParcela);
            }
        }
    }

    if (isset($_POST['parcelaEditada']) && $numParcelas != NULL) {

        foreach ($_POST['parcela'] AS $countPost => $parcela) {
            $valor = dinheiroDeBr($_POST['valor'][$countPost]) ?? NULL;
            $data_pagamento = $_POST['data_pagamento'][$countPost] ?? NULL;

            $sqlParcelas = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) VALUES ('$idPedido', '$parcela', '$valor', '$data_pagamento')";

            if ($con->query($sqlParcelas)) {
                $mensagem = mensagem('success', 'Parcelas Atualizadas!');
            } else {
                $mensagem = mensagem('danger', 'Erro ao atualizar as parcelas! Tente Novamente.');
            }
        }
    }

    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $i = $pedido['numero_parcelas'];

    $formaCompleta = "";

    $consultaParcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = $idPedido AND publicado = 1 ORDER BY numero_parcelas");

    $countForma = 0;

    while ($parcelasArray = mysqli_fetch_array($consultaParcelas)) {
        $forma = $countForma + 1 . "º parcela R$ " . dinheiroParaBr($parcelasArray['valor']) . ". Entrega de documentos a partir de " . exibirDataBr($parcelasArray['data_pagamento']) . ".\n";
        $formaCompleta = $formaCompleta . $forma;

        $countForma += 1;
    }
    $formaCompleta = $formaCompleta . "\nA liquidação de cada parcela se dará em 3 (três) dias úteis após a data de confirmação da correta execução do(s) serviço(s).";

    $sqlForma = "UPDATE pedidos SET forma_pagamento = '$formaCompleta' WHERE id = $idPedido AND origem_tipo_id = 3";
    mysqli_query($con, $sqlForma);
}

$sql = "SELECT pf.nome,
		       ec.ano,
               l.local,
               c.cargo,
               v.ano AS 'vigencia',
               v.id,
               ec.cronograma,
               ec.observacao,
               ec.pessoa_fisica_id,
               v.numero_parcelas,
               f.nome_completo AS 'fiscal',
               s.nome_completo AS 'suplente',
               verba.verba,
               p.data_kit_pagamento,
               p.numero_processo,
               p.numero_processo_mae,
               p.valor_total,
               p.justificativa,
               p.forma_pagamento,
               p.verba_id,
               ec.emia_vigencia_id,
               p.observacao
		FROM pedidos AS p
		INNER JOIN emia_contratacao AS ec ON p.origem_id = ec.id
        INNER JOIN pessoa_fisicas AS pf ON ec.pessoa_fisica_id = pf.id
        INNER JOIN locais AS l ON l.id = ec.local_id
        INNER JOIN emia_cargos AS c ON c.id = ec.emia_cargo_id
        INNER JOIN emia_vigencias AS v ON v.id = ec.emia_vigencia_id
        LEFT JOIN usuarios AS f ON ec.fiscal_id = f.id
		LEFT JOIN usuarios AS s ON ec.suplente_id = s.id
        INNER JOIN verbas AS verba ON p.verba_id = verba.id
        WHERE p.publicado = 1 AND p.id = '$idPedido' AND p.origem_tipo_id = 3";
$ec = $con->query($sql)->fetch_array();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";
$link_proposta = $http . "rlt_proposta_emia.php";
$txtJustificativa = "A EMIA - Escola Municipal de Iniciação Artística existe desde 1980 e passou a ser organizada de acordo com a Lei 15.372 em 03 de maio de 2011. Atende em média 1000 (mil) alunos, entre 5 e 13 anos, em cursos regulares e integrados nas áreas de artes visuais, dança, música e teatro. As crianças ingressam com idade entre 5 e 7 anos, por meio de sorteio público, e podem permanecer por um período de seis a oito anos. Para suprir essa demanda de alunos (distribuídos entre os cursos regulares, e paralelamente, a partir dos 7 anos, com opção de participação em cursos Optativos, Corais, Orquestra e Oficinas,) a escola necessita de um corpo docente constituído por artistas oficineiros, profissionais com formação nas quatro áreas artísticas oferecidas pela escola: Teatro, Dança, Música e Artes Visuais (Artes Plásticas). Na EMIA, o oficineiro é também um artista dentro da linguagem que trabalha com a criança. O seu projeto de trabalho com o grupo de crianças está intimamente ligado com o que ele, naquele momento, acredita e deseja realizar artisticamente. Trata-se, portanto, de um ato criador que instigará o aprendizado da criança. Diante do Decreto nº 59.283 de 16/03/2020 e Portaria 31/SMC/2020, a EMIA tem mantido suas atividades de formação artístico-pedagógicas de forma remota, propondo atividades online, na forma de produção de conteúdo para os canais da escola e da supervisão de formação e também no formato de lives, onde são ministradas atividades ao vivo por meio de plataformas de teleconferência. Quando autorizadas pela municipalidade e pelas autoridades sanitárias, as atividades presenciais poderão ser retomadas paulatinamente."
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Criar Pedido de Contratação</h2>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Dados para Contratação</h4>
            </div>
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <form method="post" action="?perfil=emia&p=pedido_contratacao&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pf">Pessoa Física: *</label>
                            <input type="hidden" name="pf" id="pf" value="<?= $ec['pessoa_fisica_id'] ?>">
                            <input type="text" value="<?= $ec['nome'] ?>" disabled class="form-control">
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
                                <?php
                                geraOpcao('verbas', $ec['verba_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="numParcelas">Número de parcelas:</label>
                            <input type="text" name="numParcelas" value="<?= $ec['numero_parcelas'] ?>"
                                   class="form-control" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="valor">Valor Total:</label>
                            <input type="text" name="valor" onKeyPress="return(moeda(this,'.',',',event))"
                                   class="form-control" value="<?= dinheiroParaBr($ec['valor_total']) ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="dataKit">Data kit pagamento: *</label>
                            <input type="date" name="dataKit" class="form-control" required
                                   placeholder="DD/MM/AAAA" value="<?= $ec['data_kit_pagamento'] ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <br>
                            <a href="?perfil=emia&p=pedido_contratacao&sp=edita_parcela&idPedido=<?= $idPedido ?>">
                                <button type="button" class="btn btn-info btn-block">Editar parcelas</button>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="numeroProcesso">Número do Processo: *</label>
                            <input type="text" name="numeroProcesso" id="numProcesso" class="form-control" required
                                   data-mask="9999.9999/9999999-9" minlength="19" value="<?= $ec['numero_processo'] ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="numeroProcesso">Número do Processo Mãe: *</label>
                            <input type="text" name="processoMae" id="processoMae" class="form-control"
                                   data-mask="9999.9999/9999999-9" minlength="19" required
                                   value="<?= $ec['numero_processo_mae'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="forma_pagamento">Forma de pagamento: </label>
                            <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                      readonly
                                      rows="8"><?= $ec['forma_pagamento'] ?></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="justificativa">Justificativa: </label>
                            <textarea id="justificativa" name="justificativa" class="form-control"
                                      rows="8"><?= $ec['justificativa'] ?? $txtJustificativa?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea id="observacao" name="observacao" class="form-control"
                                      rows="8"><?= $ec['observacao'] ?></textarea>
                        </div>
                    </div>
                </div>

                <?php
                $testaParcelasEmia = $con->query("SELECT * FROM emia_parcelas WHERE publicado = 1 AND emia_vigencia_id = " . $ec['emia_vigencia_id'])->num_rows;
                $testaParcelasPedido = $con->query("SELECT * FROM parcelas WHERE publicado = 1 AND pedido_id = " . $idPedido)->num_rows;

                if ($testaParcelasEmia > 0) {
                    $disabledEmia = "";
                    $displayEmia = "display: none;";
                } else {
                    $displayEmia = "";
                    $disabledEmia = "disabled";
                }

                if ($testaParcelasPedido > 0) {
                    $disabledPedido = "";
                    $displayPedido = "display: none;";
                } else {
                    $displayPedido = "";
                    $disabledPedido = "disabled";
                }
                ?>

                <div class="row">
                    <div class="form-group col-md-12" style="text-align: center;">
                        <span style="color: red; <?= $displayEmia ?>"><b>Não há parcelas cadastradas na vigência, lembre-se de cadastra-las para gerar a proposta</b></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12" style="text-align: center;">
                        <span style="color: red; <?= $displayPedido ?>"><b>Para gerar a proposta é necessário cadastrar as parcelas, lembre-se de cadastra-las clicando no botão (Editar parcelas)</b></span>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="col-md-5">
                        <a href="?perfil=emia&p=pedido_contratacao&sp=listagem">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
                    </div>

                    <input type="hidden" name="idEc" value="<?= $idPedido ?>" id="idEc">
                    <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                        Salvar
                    </button>
            </form>


            <div class="col-md-1">
                <form action="<?= $link_proposta ?>" target="_blank" method="post">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <button type="submit"
                            class="btn btn-primary center-block" style="width: 200%" <?= $disabledEmia . " " . $disabledPedido ?>>Gerar
                        Proposta
                    </button>
                </form>
            </div>

        </div>
</div>
</section>
</div>
