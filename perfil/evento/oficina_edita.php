<?php
$con = bancoMysqli();
$id = 1;
if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idOficina = $_POST['idOficina'] ?? NULL;
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $modalidade = $_POST['modalidade'] ?? NULL;
    $desc_modalidade = $_POST['desc_modalidade'];
    $data_inicio = $_POST ['data_inicio'];
    $data_fim = $_POST ['data_fim'];
  //  $execucao_dia1_id = $_POST['execucaod1'];
    //$execucao_dia2_id = $_POST['execucaod2'];
    $valor_hora = dinheiroDeBr($_POST['valor_hora']);
    $carga_horaria = $_POST['carga_horaria'];
}
$sqlModalidade = "INSERT INTO modalidades (id,
                                                    modalidade,
                                                    descricao)
                                                    VALUES(
                                                    '$id',
                                                    '$modalidade',
                                                    '$desc_modalidade' 
                                                    )";
if(mysqli_query($con,$sqlModalidade)){
    $id++;
    //$idModalidade = recuperaDados('oficinas','modalidade_id', $idOficina);
    //$sqlPublica = "UPDATE modalidades SET publicado = 1 WHERE id = '$idModalidade'";
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO oficinas (atracao_id, 
                                  data_inicio,
                                  data_fim,
                                  valor_hora,
                                  carga_horaria) 
                          VALUES ('$idAtracao',
                                  '$data_inicio',
                                  '$data_fim',
                                  '$valor_hora',
                                  '$carga_horaria')";

    if (mysqli_query($con, $sql)) {

        $idOficina = recuperaUltimo("oficinas");

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $sql = "UPDATE oficinas SET
                            carga_horaria = '$carga_horaria',
                            data_inicio = '$data_inicio',
                            data_fim = '$data_fim',
                            valor_hora = '$valor_hora',
                            WHERE id = '$idOficina'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if (isset($_POST['carregar'])) {
    $idOficina = $_POST['idOficina'];
}

$oficina = recuperaDados("oficinas", "id", $idOficina);
$modalidade = recuperaDados('modalidades','id', $idAtracao);

include "includes/menu_interno.php";

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="modalidade">Modalidade:</label>
                                            <input type="text" id="modalidade"  name="modalidade" class="form-control" value="<?=$modalidade['modalidade']?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="desc_modalidade">Descrição da Modalidade:</label><br/>
                                            <textarea name="desc_modalidade" id="desc_modalidade" class="form-control"
                                                      rows="3" ><?=$modalidade['descricao']?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="valor_hora">Valor hora/aula: </label><br>
                                            <input class="form-control" style="max-width: 175px;" type="tel" name="valor_hora"
                                                   onkeypress="return(moeda(this, '.', ',', event))" value="<?=dinheiroParaBr($oficina['valor_hora'])?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="carga_horaria">Carga Horária (em horas): </label><br>
                                            <input class="form-control" style="max-width: 175px;" type="number"
                                                   name="carga_horaria" value="<?=$oficina['carga_horaria']?>"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="data_inicio">Início de inscrição: </label> <br/>
                                            <input class="form-control" style="max-width: 175px;" type="date" name="data_inicio" value="<?=$oficina['data_inicio']?>"
                                                   onkeyup="barraData(this);"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="data_fim">Encerramento de inscrição: </label> <br>
                                            <input class="form-control" style="max-width: 175px;" type="date" name="data_fim" value="<?=$oficina['data_fim']?>"/>
                                        </div>
                                    </div>

                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idOficina" value="<?= $idOficina ?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Salvar</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
