<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['editar'])) {
    $idPf = recuperaUltimo("pessoa_fisicas");
    $ano = $_POST['ano'];
    $status = "1";
    $chamado = $_POST['chamado'];
    $classificacao_indicativa = $_POST['classificacao'];
    $territorio = $_POST['territorio'];
    $coordenadoria = $_POST['coordenadoria'];
    $subprefeitura = $_POST['subprefeitura'];
    $programa = $_POST['programa'];
    $linguagem = $_POST['linguagem'];
    $projeto = $_POST['projeto'];
    $cargo = $_POST['cargo'];
    $vigencia = $_POST['vigencia'];
    $observacao = $_POST['observacao'];
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $data = date("Y-m-d H:i:s", strtotime("now"));
}
if (isset($_POST['cadastra'])) {
    $sqlInsert = "INSERT INTO formacao_contratacoes ( 
                                   pessoa_fisica_id, 
                                   ano, 
                                   form_status_id, 
                                   chamado, 
                                   classificacao, 
                                   territorio_id, 
                                   coordenadoria_id, 
                                   subprefeitura_id, 
                                   programa_id, 
                                   linguagem_id, 
                                   projeto_id, 
                                   form_cargo_id, 
                                   form_vigencia_id, 
                                   observacao, 
                                   fiscal_id, 
                                   suplente_id,  
                                   usuario_id, 
                                   data_envio 
                                   )
                                   VALUES(
                                          '$idPf',
                                          '$ano',
                                          '$status',
                                          '$chamado',
                                          '$classificacao_indicativa',
                                          '$territorio',
                                          '$coordenadoria',
                                          '$subprefeitura',
                                          '$programa',
                                          '$linguagem',
                                          '$projeto',
                                          '$cargo',
                                          '$vigencia',
                                          '$observacao',
                                          '$fiscal',
                                          '$suplente',
                                          '$usuario',
                                          '$data')";
    $queryInsert = mysqli_query($con, $sqlInsert);
}

if (isset($_POST['editar'])) {
    $idContrat = recuperaUltimo('formacao_contratacoes');
    $sqlUpdate = "UPDATE formacao_contratacoes SET 
                                 ano = '$ano',
                                 chamado = '$chamado',
                                 classificacao = '$classificacao_indicativa',
                                 territorio_id = '$territorio',
                                 coordenadoria_id = '$coordenadoria',
                                 subprefeitura_id = '$subprefeitura',
                                 programa_id = '$programa',
                                 linguagem_id = '$linguagem',
                                 projeto_id = '$projeto',
                                 form_cargo_id = '$cargo',
                                 form_vigencia_id = '$vigencia',
                                 observacao = '$observacao',
                                 fiscal_id = '$fiscal',
                                 suplente_id = '$suplente'
                                WHERE id = '$idContrat'";

    $queryUpdate = mysqli_query($con, $sqlUpdate);
}

//$form_contr = recuperaDados('formacao_contratacoes');

?>
<div class="content-wrapper">
    <div class="content">
        <h2 class="page-header">Cadastro de Pedido de Contratação</h2>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Pedido de Contratação</h4>
            </div>
            <form action="?perfil=formacao&p=pedido_contratacao&sp=editar" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="ano">Ano *</label>
                            <input type="number" min="2018" id="ano" name="ano" required class="form-control"
                                   value="<? //$form_contr['ano'] ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="chamado">Chamado *</label>
                            <label><input type="radio" name="chamado" value="1" checked> Sim </label>&nbsp;&nbsp;
                            <label><input type="radio" name="chamado" value="0"> Não </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa *</label>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-default"><i class="fa fa-info"></i></button>
                            <select class="form-control" name="classificacao" id="classificacao" required>
                                <option></option>
                                <?php
                                geraOpcao("classificacao_indicativas");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="territorio">Território *</label>
                            <select class="form-control" name="territorio" id="territorio" required>
                                <option></option>
                                <?php
                                geraOpcao("territorios");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="coordenadoria">Coordenadoria *</label>
                            <select class="form-control" name="coordenadoria" id="coordenadoria" required>
                                <option></option>
                                <?php
                                geraOpcao("coordenadorias");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="subprefeitura">Subprefeitura *</label>
                            <select class="form-control" name="subprefeitura" id="subprefeitura" required>
                                <option></option>
                                <?php
                                geraOpcao("subprefeituras");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="programa">Programa *</label>
                            <select class="form-control" name="programa" id="programa" required>
                                <option></option>
                                <?php
                                geraOpcao("programas");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="linguagem">Linguagem *</label>
                            <select class="form-control" name="linguagem" id="linguagem" required>
                                <option></option>
                                <?php
                                geraOpcao("linguagens");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="projeto">Projeto *</label>
                            <select class="form-control" name="projeto" id="projeto" required>
                                <option></option>
                                <?php
                                geraOpcao("projetos");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cargo">Cargo *</label>
                            <select class="form-control" name="cargo" id="cargo" required>
                                <option></option>
                                <?php
                                geraOpcao("formacao_cargos");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="vigencia">Vigência *</label>
                            <select class="form-control" name="vigencia" id="vigencia" required>
                                <option></option>
                                <?php
                                geraOpcao("formacao_vigencias");
                                ?>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3"
                                      class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fiscal">Fiscal *</label>
                            <select name="fiscal" id="fiscal" class="form-control" required>
                                <option></option>
                                <?php
                                geraOpcaoUsuario('usuarios', 1, $evento['fiscal_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fiscal">Suplente </label>
                            <select name="suplente" id="suplente" class="form-control">
                                <option></option>
                                <?php
                                geraOpcaoUsuario('usuarios', 1, $evento['suplente_id']);
                                ?>
                            </select>
                        </div>
                    </div>

            </form>
        </div>
        <div class="box-footer">
            <a href="?perfil&p=pedido_contratacao&sp=listagem">
                <button type="button" class="btn btn-default">Voltar</button>
            </a>
            <button type="submit" name="editar" id="editar" class="btn btn-primary pull-right">
                Salvar
            </button>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Classificação Indicativa</strong></h4>
            </div>
            <div class="modal-body">
                <h4><strong>Informação e Liberdade de Escolha</strong></h4>
                <p align="justify">A Classificação Indicativa é um conjunto de informações sobre o conteúdo de obras
                    audiovisuais e diversões públicas quanto à adequação de horário, local e faixa etária. Ela alerta os
                    pais ou responsáveis sobre a adequação da programação à idade de crianças e adolescentes. É da
                    Secretaria Nacional de Justiça (SNJ), do Ministério da Justiça (MJ), a responsabilidade da
                    Classificação Indicativa de programas TV, filmes, espetáculos, jogos eletrônicos e de interpretação
                    (RPG).</p>
                <p align="justify">Programas jornalísticos ou noticiosos, esportivos, propagandas eleitorais e
                    publicidade, espetáculos circenses, teatrais e shows musicais não são classificados pelo Ministério
                    da Justiça e podem ser exibidos em qualquer horário.</p>
                <p align="justify">Os programas ao vivo poderão ser classificados se apresentarem inadequações, a partir
                    de monitoramento ou denúncia.</p>
                <p align="justify">
                    <strong>Livre:</strong> Não expõe crianças a conteúdos potencialmente prejudiciais. Exibição em
                    qualquer horário.<br>
                    <strong>10 anos:</strong> Conteúdo violento ou linguagem inapropriada para crianças, ainda que em
                    menor intensidade. Exibição em qualquer horário.<br>
                    <strong>12 anos:</strong> As cenas podem conter agressão física, consumo de drogas e insinuação
                    sexual. Exibição a partir das 20h.<br>
                    <strong>14 anos:</strong> Conteúdos mais violentos e/ou de linguagem sexual mais acentuada. Exibição
                    a partir das 21h.<br>
                    <strong>16 anos:</strong> Conteúdos mais violentos ou com conteúdo sexual mais intenso, com cenas de
                    tortura, suicídio, estupro ou nudez total. Exibição a partir das 22h.<br>
                    <strong>18 anos:</strong> Conteúdos violentos e sexuais extremos. Cenas de sexo, incesto ou atos
                    repetidos de tortura, mutilação ou abuso sexual. Exibição a partir das 23h.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
</div>