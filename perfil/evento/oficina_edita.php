<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $idOficina = $_POST['idOficina'] ?? NULL;
    $idForma_inscricao = $_POST['inscricao'] ?? NULL;
    $certificado = ($_POST['certificado']);
    $vagas = $_POST['vagas'];
    $venda = ($_POST['venda']);
    $publico_alvo = addslashes($_POST['publico_alvo']);
    $material = addslashes($_POST['material']);
    $carga_horaria = $_POST['carga_horaria'];
    $valor_hora = dinheiroDeBr($_POST['valor_hora']);
    $inicio = $_POST ['inicio_inscricao'];
    $encerramento = $_POST ['encerramento_inscricao'];
    $divulgacao = $_POST['data_divulgacao'];
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO oficinas (atracao_id, 
                                  forma_inscricao_id,
                                  certificado,
                                  vagas,
                                  venda,
                                  publico_alvo,
                                  material_requisitado,
                                  carga_horaria,
                                  inicio_inscricao,
                                  encerramento_inscricao,
                                  valor_hora,
                                  data_divulgacao) 
                          VALUES ('$idAtracao',
                                  '$idForma_inscricao',
                                  '$certificado',
                                  '$vagas',
                                  '$venda',
                                  '$publico_alvo',
                                  '$material',
                                  '$carga_horaria',
                                  '$inicio',
                                  '$encerramento',
                                  '$valor_hora',
                                  '$divulgacao')";

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
                            forma_inscricao_id = '$idForma_inscricao',
                            certificado = '$certificado',
                            vagas = '$vagas',
                            venda = '$venda',
                            publico_alvo = '$publico_alvo',
                            material_requisitado = '$material',
                            carga_horaria = '$carga_horaria',
                            inicio_inscricao = '$inicio',
                            encerramento_inscricao = '$encerramento',
                            valor_hora = '$valor_hora',
                            data_divulgacao = '$divulgacao'
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
                                    <label for="certificado">Certficado?</label><br/>
                                    <label><input type="radio" name="certificado"
                                                  value="1" <?= $oficina['certificado'] == 1 ? 'checked' : NULL ?> > Sim
                                    </label>
                                    <label><input type="radio" name="certificado"
                                                  value="0" <?= $oficina['certificado'] == 0 ? 'checked' : NULL ?> > Não
                                    </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="vagas">Vagas</label> <br>
                                    <input class="form-control" style="max-width: 175px;" type="number" name="vagas"
                                           value="<?= $oficina['vagas'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="venda">Venda de material?</label> <br>
                                    <label><input type="radio" name="venda" class="venda"
                                                  value="1" <?= $oficina['venda'] == 1 ? 'checked' : NULL ?> id="sim"> Sim
                                    </label>
                                    <label><input type="radio" name="venda" class="venda"
                                                  value="0" <?= $oficina['venda'] == 0 ? 'checked' : NULL ?> id="nao"> Não
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="publico_alvo">Público-alvo *</label>
                                    <textarea name="publico_alvo" id="publico_alvo" class="form-control"
                                              rows="5"><?= $oficina['publico_alvo'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="material">Material Requisitado: </label>
                                    <textarea name="material" id="material" class="form-control"
                                              rows="2" readonly><?= $oficina['material_requisitado'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="inscricao">Forma de inscrição: </label><br>
                                    <select class="form-control" style="max-width: 175px;" id="inscricao"
                                            name="inscricao">
                                        <option value="0">Selecione</option>
                                        <?php geraOpcao('forma_inscricao', $oficina['forma_inscricao_id']) ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="carga_horaria">Carga Horária (em horas): </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="number"
                                           name="carga_horaria" value="<?= $oficina['carga_horaria'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="valor_hora">Valor hora/aula:</label><br>
                                    <input class="form-control" style="max-width: 175px;" type="tel" name="valor_hora"
                                           placeholder="5,00" onkeypress="return(moeda(this, '.', ',', event))"
                                           value="<?= dinheiroParaBr($oficina['valor_hora']) ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="inicio_inscricao">Início de inscrição: </label> <br/>
                                    <input class="form-control" style="max-width: 175px;" type="date"
                                           name="inicio_inscricao" value="<?= $oficina['inicio_inscricao'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="encerramento_inscricao">Encerramento de inscrição: </label>
                                    <input class="form-control" style="max-width: 175px;" type="date"
                                           name="encerramento_inscricao"
                                           value="<?= $oficina['encerramento_inscricao'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="data_divulgacao">Data de divulgação da inscrição: </label> <br/>
                                    <input class="form-control" style="max-width: 175px;" type="date"
                                           name="data_divulgacao" value="<?= $oficina['data_divulgacao'] ?>">
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


<script>
    var venda = $('.venda');
    venda.on("change", verificaVenda);
    $(document).ready(verificaVenda());

    function verificaVenda () {
        if ($('#sim').is(':checked')) {
            $('#material    ')
                .attr('readonly', false)
        } else {
            $('#material')
                .attr('readonly', true)
                .val('');
        }
    }
</script>
