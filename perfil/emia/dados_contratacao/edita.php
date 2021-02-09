<?php
$con = bancoMysqli();

if (isset($_POST['cadastrar']) || isset($_POST['editar'])){
    $idPf = $_POST['pf'];
    $ano = $_POST['ano'];
    $local = $_POST['local'];
    $cargo = $_POST['cargo'];
    $vigencia = $_POST['vigencia'];
    $cronograma = trim(addslashes($_POST['cronograma'])) ?? NULL;
    $obs = trim(addslashes($_POST['observacao'])) ?? NULL;
    $status = "1";
    $fiscal = $_POST['fiscal'] ?? NULL;
    $suplente = $_POST['suplente'] ?? NULL;
    $usuario = $_SESSION['usuario_id_s'];
    $data = date("Y-m-d H:i:s", strtotime("-3 hours"));
}

if (isset($_POST['cadastrar'])) {

    $sqlInsert = "INSERT INTO emia_contratacao
                                    (pessoa_fisica_id, 
                                     ano, 
                                     emia_status_id, 
                                     local_id, 
                                     emia_cargo_id, 
                                     emia_vigencia_id, 
                                     cronograma, 
                                     observacao, 
                                     fiscal_id, 
                                     suplente_id, 
                                     usuario_id, 
                                     data_envio)
                                     VALUES
                                        ('$idPf',
                                         '$ano',
                                         '$status',
                                         '$local',
                                         '$cargo',
                                         '$vigencia',
                                         '$cronograma',
                                         '$obs',
                                         '$fiscal',
                                         '$suplente',
                                         '$usuario',
                                         '$data') ";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem('success', 'Cadastrado com Sucesso!');
        $idEC = recuperaUltimo('emia_contratacao');
        $protocolo = geraProtocolo($idEC) . "-M";
        $sqlProtocolo = "UPDATE emia_contratacao SET protocolo = '$protocolo' WHERE id = '$idEC'";
        $queryProtocolo = mysqli_query($con, $sqlProtocolo);
    } else {
        $mensagem = mensagem('danger', 'Erro ao Cadastrar! Tente novamente.');
    }
}

if (isset($_POST['editar'])) {
    $idEC = $_POST['idEC'];

    $sqlUpdate = "UPDATE emia_contratacao SET
                    pessoa_fisica_id = '$idPf',
                                 ano = '$ano',
                                 emia_cargo_id = '$cargo',
                                 emia_vigencia_id = '$vigencia',
                                 observacao = '$obs',
                                 cronograma = '$cronograma',
                                 local_id = '$local',
                                 fiscal_id = '$fiscal',
                                 suplente_id = '$suplente'
                                WHERE id = '$idEC'";

    if (mysqli_query($con, $sqlUpdate)) {
        $mensagem = mensagem("success", "Salvo com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro aos salvar! Tente novamente.");
    }
}

if (isset($_POST['edit'])) {
    $idEC = $_POST['idECEdit'];
}
$ec = recuperaDados('emia_contratacao', 'id', $idEC);

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>EMIA - Dados para contratação</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Edição de dados para Contratação</h4>
            </div>
            <div class="box-body">
                <form action="?perfil=emia&p=dados_contratacao&sp=edita" method="POST" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pf">Pessoa Física: *</label>
                            <select name="pf" id="pf" class="form-control select2bs4" required>
                                <option value="">Selecione uma pessoa física...</option>
                                <?php
                                geraOpcao('pessoa_fisicas', $ec['pessoa_fisica_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="ano">Ano: *</label>
                            <input name="ano" id="ano" type="number" min="2018" required class="form-control"
                                   value="<?= $ec['ano'] ?>">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="local">Local: *</label>
                            <select name="local" id="local" required class="form-control select2bs4">
                                <option value="">Selecione um local...</option>
                                <?php
                                geraOpcao('locais', $ec['local_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <select name="cargo" id="cargo" class="form-control" required>
                                <option value="">Selecione um cargo...</option>
                                <?php
                                geraOpcao('emia_cargos', $ec['emia_cargo_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="vigencia">Vigência: *</label>
                            <select class="form-control" name="vigencia" id="vigencia" required>
                                <?php
                                $opcoesVigencia = $con->query("SELECT id, ano, descricao FROM emia_vigencias WHERE publicado = 1");
                                if ($opcoesVigencia->num_rows > 0) {
                                    while ($opcoesArray = mysqli_fetch_row($opcoesVigencia)) {
                                        if ($opcoesArray[0] == $ec['emia_vigencia_id']) { ?>
                                            <option value="<?= $opcoesArray[0] ?>"
                                                    selected> <?= $opcoesArray[1] . " (" . $opcoesArray[2] . ")" ?> </option>
                                        <?php } else { ?>
                                            <option value="<?= $opcoesArray[0] ?>"> <?= $opcoesArray[1] . " (" . $opcoesArray[2] . ")" ?> </option>
                                        <?php }
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span id="msgEscondeAno" class="pull-right" style="color: red;"><b>Ano escolhido é maior que a vigência!</b></span>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="cronograma">Cronograma: </label>
                            <textarea name="cronograma" id="cronograma" rows="3" type="text"
                                      class="form-control"><?= $ec['cronograma'] ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3" type="text"
                                      class="form-control"><?= $ec['observacao'] ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control select2bs4">
                                <option value="">Selecione um fiscal...</option>
                                <?php
                                geraOpcaoUsuario("usuarios", 1, $ec['fiscal_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
                            <select name="suplente" id="suplente" class="form-control select2bs4">
                                <option value="">Selecione um suplente...</option>
                                <?php
                                geraOpcaoUsuario("usuarios", 1, $ec['suplente_id']);
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=emia&p=dados_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <input type="hidden" name="idEC" value="<?= $idEC ?>" id="idEC">
                <button type="submit" class="btn btn-primary pull-right" name="editar" id="editar">Salvar</button>
                </form>
                <hr>
                <form action="?perfil=emia&p=pedido_contratacao&sp=cadastra" method="POST">
                    <input type="hidden" name="idDados" value="<?= $idEC ?>">
                    <button type="submit" style="width: 30%" class="btn btn-success center-block">Gerar pedido de
                        contratação
                    </button>
                </form>
            </div>

        </div>
    </section>
</div>

<script>
    let ano = $('#ano');
    let vigencia = $('#vigencia');
    let botao = $('#cadastra');
    var isMsgAno = $('#msgEscondeAno');
    isMsgAno.hide();

    function maior() {
        let valorVigencia = $('#vigencia option:selected').text();
        valorVigencia = parseInt(valorVigencia.substring(0, 5))
        if (ano.val() > valorVigencia) {
            botao.prop('disabled', true);
            isMsgAno.show();
        } else {
            botao.prop('disabled', false);
            isMsgAno.hide();
        }
    }

    ano.on('change', maior);
    vigencia.on('change', maior);

    $(document).ready(maior);
</script>
