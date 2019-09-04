<?php
$con = bancoMysqli();

if(isset($_POST['cadastra']) || isset($_POST['edita'])){
  $idPc = $_POST['idPc'];
  $fc = recuperaDados('formacao_contratacoes', 'id', $idPc);
  $idPf = $fc['pessoa_fisica_id'];
  $verba = $_POST['verba'];
  $numParcelas = $_POST['numParcelas'];
  $valor = dinheiroDeBr($_POST['valor']);
  $dataKit = $_POST['dataKit'];
  $numeroProcesso = $_POST['numeroProcesso'];
  $forma_pagamento = addslashes($_POST['forma_pagamento']) ?? null;
  $justificativa = addslashes($_POST['justificativa']) ?? null;
  $observacao = addslashes($_POST['observacao']) ?? null;

  if(isset($_POST['cadastra'])){
    $sql = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, pessoa_fisica_id, numero_processo, verba_id, numero_parcelas, valor_total, forma_pagamento, data_kit_pagamento, justificativa, status_pedido_id, observacao)
                         VALUES (2, '$idPc', 1, '$idPf', '$numeroProcesso', '$verba', '$numParcelas', '$valor', '$forma_pagamento', '$dataKit', '$justificativa', 2, '$observacao')";
    if(mysqli_query($con, $sql)){
      $idPedido = recuperaUltimo('pedidos');
      $idVigencia = recuperaDados('formacao_vigencias', 'id', $fc['form_vigencia_id'])['id'];
      $sqlParcela = "SELECT * FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia' AND publicado = 1";
      $queryParcela = mysqli_query($con, $sqlParcela);
      $arrayParcela = mysqli_fetch_array($queryParcela);
      $rowParcela = mysqli_num_rows($queryParcela);

      if($rowParcela > 0){
        foreach ($arrayParcela as $rowP) {
          print($rowP);
        }
      }
    } else {

    }
  }
}

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
$sql = "SELECT valor FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia' AND publicado = 1";
$query = mysqli_query($con, $sql);
$valores = mysqli_fetch_array($query);
$rows = mysqli_num_rows($query);
if($rows > 0){
  for($count = 0; $count < $rows; $count++)
    $valor += $valores[$count];
}
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
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <form method="post" action="?perfil=formacao&p=dados_contratacao&sp=editar" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="ano">Ano: *</label>
                            <input type="number" min="2018" id="ano" name="ano" required class="form-control"
                                   value="<?= $fc['ano'] ?>" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="chamado">Chamado: *</label>
                            <input type="number" min="0" max="127" id="chamado" name="chamado"
                                   value="<?= $fc['chamado'] ?>" required class="form-control" readonly>
                        </div>

                    </div>

                    <div class="row">
                        <div class="from-group col-md-12">
                            <label for="pf">Pessoa Física: *</label>
                            <input type="text" class="form-control" name="pessoa_fisica" id="pessoa_fisica"
                                   value="<?= $pessoa_fisica ?>" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa *</label>
                            <input type="text" name="classificacao" value="<?= $classificacao ?>" readonly
                                   class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="territorio">Território *</label>
                            <input type="text" name="territorio" value="<?= $territorio ?>" readonly
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="coordenadoria">Coordenadoria *</label>
                            <input type="text" name="coordenadoria" value="<?= $coordenadoria ?>" readonly
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="subprefeitura">Subprefeitura *</label>
                            <input type="text" name="subprefeitura" value="<?= $subprefeitura ?>" readonly
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="programa">Programa *</label>
                            <input type="text" name="programa" value="<?= $programa ?>" readonly class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="linguagem">Linguagem *</label>
                            <input type="text" name="linguagem" value="<?= $linguagem ?>" readonly class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="projeto">Projeto *</label>
                            <input type="text" name="projeto" value="<?= $projeto ?>" readonly class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cargo">Cargo *</label>
                            <input type="text" name="cargo" value="<?= $cargo ?>" readonly class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="vigencia">Vigência *</label>
                            <input type="text" name="vigencia" value="<?= $vigencia['ano'] ?>" readonly class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3"
                                      class="form-control" readonly><?= $fc['observacao'] ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fiscal">Fiscal *</label>
                            <input type="text" name="fiscal" value="<?= $fiscal ?>" readonly class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fiscal">Suplente </label>
                            <input type="text" name="suplente" value="<?= $suplente ?>" readonly class="form-control">
                        </div>
                    </div>


                    <hr>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="verba">Verba* </label>
                            <select name="verba" id="verba" class="form-control">
                                <?php geraOpcao('verbas'); ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="numParcelas">Número de parcelas</label>
                          <input type="text" name="numParcelas" value="<?= $numParcelas ?>" readonly class="form-control" required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="valor">Valor</label>
                          <input type="text" name="valor" onKeyPress="return(moeda(this,'.',',',event))" class="form-control" value="<?= $valor ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-md-3">
                        <label for="dataKit">Data kit pagamento</label>
                        <input type="date" name="dataKit" class="form-control" id="datepicker10"
                               placeholder="DD/MM/AAAA">
                      </div>

                      <div class="form-group col-md-3">
                        <label for="numeroProcesso">Número do Processo *</label>
                        <input type="number" min="0" name="numeroProcesso" id="numeroProcesso" class="form-control">
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-md-6">
                          <label for="forma_pagamento">Forma de pagamento *</label>
                          <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                    rows="8"></textarea>
                      </div>

                      <div class="form-group col-md-6">
                          <label for="justificativa">Justificativa *</label>
                          <textarea id="justificativa" name="justificativa" class="form-control"
                                    rows="8"></textarea>
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-md-12">
                          <label for="justificativa">Observação *</label>
                          <textarea id="observacao" name="observacao" class="form-control"
                                    rows="8"></textarea>
                    </div>
                </div>


                <div class="box-footer">
                    <a href="?perfil=formacao&p=dados_contratacao&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>

                    <input type="hidden" name="idFC" value="<?= $idPC ?>" id="idFC">

                    <button type="submit" name="editar" id="editar" class="btn btn-primary pull-right">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
