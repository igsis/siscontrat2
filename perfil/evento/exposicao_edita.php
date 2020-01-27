<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $idExposicao = $_POST['idExposicao'] ?? NULL;
    $tipo_exposicao_id = $_POST['tipo_exposicao'] ?? NULL;
    $contratados = ($_POST['contratados']);
    $painel = addslashes($_POST['painel']);
    $legenda = $_POST['legenda'];
    $identidade = $_POST['identidade'];
    $suporte = $_POST['suporte'];
    $documentacao = $_POST['documentacao'];
    $acervo = $_POST ['acervo'];

}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO exposicoes (atracao_id, 
                                  numero_contratados,
                                  tipo_exposicao_id,
                                  painel,
                                  legendas,
                                  identidade,
                                  suporte,
                                  documentacao,
                                  acervo) 
                          VALUES ('$idAtracao',
                                  '$contratados',
                                  '$tipo_exposicao_id',
                                  '$painel',
                                  '$legenda',
                                  '$identidade',
                                  '$suporte',
                                  '$documentacao',
                                  '$acervo')";

    if (mysqli_query($con, $sql)) {

        $idExposicao = recuperaUltimo("exposicoes");

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $sql = "UPDATE exposicoes SET
                            numero_contratados = '$contratados',
                            tipo_exposicao_id = '$tipo_exposicao_id',
                            painel = '$painel',
                            legendas = '$legenda',
                            identidade = '$identidade',
                            suporte = '$suporte',
                            documentacao = '$documentacao',
                            acervo = '$acervo'
                            WHERE id = '$idExposicao'";


    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if (isset($_POST['carregar'])) {
    $idExposicao = $_POST['idExposicao'];
}

$exposicao = recuperaDados("exposicoes", "id", $idExposicao);


include "includes/menu_interno.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Especificidade</h2>

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
                    <form method="POST" action="?perfil=evento&p=exposicao_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="contratados">Quantidade de contratados *</label><br/>
                                    <input class="form-control" type="number" name="contratados" id="contratados"
                                                  value="<?= $exposicao['numero_contratados'] ?>" required>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="tipo_exposicao">Tipo de Exposição *</label> <br>
                                    <select class="form-control" id="tipo_exposicao" name="tipo_exposicao" required>
                                            <option value="0">Selecione</option>
                                            <?php geraOpcao('tipo_exposicao', $exposicao['tipo_exposicao_id']) ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="painel">Confecção de painéis</label> <br>
                                    <label><input type="radio" name="painel"
                                                  value="1" <?= $exposicao['painel'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>
                                    <label><input type="radio" name="painel"
                                                  value="0" <?= $exposicao['painel'] == 0 ? 'checked' : NULL ?>> Não
                                    </label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="legenda">Confecção de legendas</label> <br>
                                    <label><input type="radio" name="legenda"
                                                  value="1" <?= $exposicao['legendas'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>
                                    <label><input type="radio" name="legenda"
                                                  value="0" <?= $exposicao['legendas'] == 0 ? 'checked' : NULL ?>> Não
                                    </label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="identidade">Criação de Identidade Visual</label> <br>
                                    <label><input type="radio" name="identidade"
                                                  value="1" <?= $exposicao['identidade'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>
                                    <label><input type="radio" name="identidade"
                                                  value="0" <?= $exposicao['identidade'] == 0 ? 'checked' : NULL ?>> Não
                                    </label>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="suporte">Suporte extra (exposição)</label> <br>
                                    <label><input type="radio" name="suporte"
                                                  value="1" <?= $exposicao['suporte'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>
                                    <label><input type="radio" name="suporte"
                                                  value="0" <?= $exposicao['suporte'] == 0 ? 'checked' : NULL ?>> Não
                                    </label>
                                </div>
                            </div>

                            <div class="row">    
                                <div class="form-group col-md-6">
                                    <label for="documentacao">Pedido de documentação</label> <br>
                                    <label><input type="radio" name="documentacao" id="fotografia"
                                                  value="2" <?= $exposicao['documentacao'] == 2 ? 'checked' : NULL ?>>
                                        Fotografia </label>
                                    <label><input type="radio" name="documentacao" id="audio"
                                                  value="1" <?= $exposicao['documentacao'] == 1 ? 'checked' : NULL ?>>
                                        Áudio </label>
                                    <label><input type="radio" name="documentacao" id="video"
                                                  value="0" <?= $exposicao['documentacao'] == 0 ? 'checked' : NULL ?>>
                                        Vídeo </label>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="acervo">Acervo </label><br>
                                    <select class="form-control" id="acervo" name="acervo">
                                        <option value="1" <?= $exposicao['acervo'] == 1 ? 'selected' : NULL ?> >A
                                            exposição NÃO possui peças que fazem parte da coleção da instituição.
                                        </option>
                                        <option value="2" <?= $exposicao['acervo'] == 2 ? 'selected' : NULL ?> >A
                                            exposição POSSUI peças que fazem parte da coleção da instituição.
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idExposicao" value="<?= $idExposicao ?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Salvar</button>
                            </div>
                        </div>

                </div>
                </form>
            </div>
        </div>
    </section>
</div>

