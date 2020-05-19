<?php
$con = bancoMysqli();

if(isset($_POST['cadastra']) || isset($_POST['editar'])){
    $idEV = $_POST['idEV'] ?? NULL;
    $ano = $_POST['ano'] ?? NULL;
    $desc = trim(addslashes($_POST['desc'])) ?? NULL;
    $numParcela = $_POST['numParcelas'] ?? NULL;
}

if (isset($_POST['cadastra'])) {
    $sqlInsert = "INSERT INTO emia_vigencias
                            (ano, descricao,numero_parcelas)
                            VALUES
                            ('$ano', '$desc', '$numParcela')";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        $idEV = recuperaUltimo('emia_vigencias');
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar! Tente novamente.");
    }
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

if (isset($_POST['editar'])) {
    $sqlUpdate = "UPDATE emia_vigencias SET
                    ano = '$ano',
                    descricao = '$desc',
                    numero_parcelas = '$numParcela'
                    WHERE id = '$idEV'";

    $sql = "DELETE FROM emia_parcelas WHERE emia_vigencia_id = '$idEV'";
    mysqli_query($con, $sql);

    $parcelas = $_POST['parcela'];
    $valores = dinheiroDeBr($_POST['valor']);
    $data_inicios = $_POST['data_inicio'];
    $data_fins = $_POST['data_fim'];
    $data_pagamentos = $_POST['data_pagamento'];
    $mes_refs = $_POST['mes_ref'];
    $cargas = $_POST['carga'];

    $i = $numParcela;

    for ($count = 0; $count < $i; $count++) {
        $parcela = $parcelas[$count] ?? NULL;
        $valor = $valores[$count] ?? NULL;
        $data_inicio = $data_inicios[$count] ?? NULL;
        $data_fim = $data_fins[$count] ?? NULL;
        $data_pagamento = $data_pagamentos[$count] ?? NULL;
        $mes_ref = $mes_refs[$count] ?? NULL;
        $carga = $cargas[$count] ?? NULL;

        $sql = "INSERT INTO emia_parcelas (emia_vigencia_id, numero_parcelas, valor, data_inicio, data_fim, data_pagamento, mes_referencia_id, carga_horaria)
                                       VALUES ('$idEV', '$parcela', '$valor', '$data_inicio', '$data_fim', '$data_pagamento', '$mes_ref', '$carga')";

        if(mysqli_query($con, $sql)){
            $mensagem = mensagem("success", "Gravado com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        }
    }

    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

if (isset($_POST['edit'])) {
    $idEV = $_POST['idEVEdit'];
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Cadastro de Vigência</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header with-border">
                <h2 class="box-title">Edição de Vigência</h2>
            </div>
            <form method="post" action="?perfil=emia&p=administrativo&sp=vigencia&spp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="ano">Ano: *</label>
                            <input class="form-control" type="number" min="2018" required name="ano" id="ano"
                                   value="<?= $ev['ano'] ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="num_parcela">Numero de Parcelas: *</label>
                            <input type="number" min="1" id="numParcelas" name="numParcelas" required
                                   class="form-control"
                                   value="<?= $ev['numero_parcelas'] ?>">
                        </div>

                        <div class="col-md-8">
                            <label for="descricao">Descrição: *</label>
                            <input class="form-control" type="text" required name="desc" id="desc"
                                   value="<?= $ev['descricao'] ?>">
                        </div>
                    </div>



                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastre as parcelas</h3>
                    </div>
                    <br>

                    <?php
                    for ($i = 1; $i < $ev['numero_parcelas'] + 1; $i++) {
                        $sql = "SELECT * FROM emia_parcelas WHERE emia_vigencia_id = '$idEV' AND numero_parcelas = '$i' AND publicado = '1'";
                        $parcelas = mysqli_fetch_array(mysqli_query($con, $sql));
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="parcela[]">Parcela:</label>
                                <input type="number" readonly class="form-control" value="<?= $i ?>"
                                       name="parcela[]" id="parcela[<?= $i ?>]">
                            </div>
                        </div>

                    <div class="row">
                            <div class="form-group col-md-2">
                                <label for="valor[]">Valor:</label>
                                <input type="text" id="valor<?= $i ?>" name="valor[]"
                                       class="form-control" onKeyPress="return(moeda(this,'.',',',event))" value="<?= dinheiroParaBr($parcelas['valor'] ?? NULL) ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="data_inicio">Data inicial:</label>
                                <input type="date" name="data_inicio[]" class="form-control" id="data_inicio<?=$i?>"
                                       placeholder="DD/MM/AAAA" value="<?= $parcelas['data_inicio'] ?? NULL ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="data_fim">Data final: </label>
                                <input type="date" name="data_fim[]" class="form-control" id="data_fim<?= $i ?>"
                                       placeholder="DD/MM/AAAA" value="<?= $parcelas['data_fim'] ?? NULL ?>">
                            </div>


                            <div class="form-group col-md-2">
                                <label for="data_pagamento">Data pagamento: </label>
                                <input type="date" name="data_pagamento[]" class="form-control"
                                       id="data_pagamento<?= $i ?>" placeholder="DD/MM/AAAA" value="<?= $parcelas['data_pagamento'] ?? NULL ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="carga[]">Carga horária: </label>
                                <input type="number" name="carga[]" class="form-control" id="carga<?= $i ?>"
                                       value="<?= $parcelas['carga_horaria'] ?? NULL ?>"  min="1">
                            </div>

                        <div class="form-group col-md-2">
                            <label for="mes_ref[]">Mês Referencia: *</label>
                            <select name="mes_ref[]" id="mes_ref<?= $i ?>" class="form-control">
                                <?php
                                    geraOpcaoParcelas("emia_meses", $parcelas['mes_referencia_id']);
                                ?>
                            </select>
                        </div>

                        </div>
                <?php } ?>
                            
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=administrativo&sp=vigencia&spp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" name="idEV" value="<?= $ev['id'] ?>" id="idEV">
                    <button name="editar" id="editar" type="submit" class="btn btn-primary pull-right">Salvar</button>

            </form>
                </div>
        </div>
    </section>
</div>

