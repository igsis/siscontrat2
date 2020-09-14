<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['editar'])) {
    $idPF = $_POST['idPF'];
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
    $observacao = trim(addslashes($_POST['observacao']));
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'] ?? NULL;
    $usuario = $_SESSION['usuario_id_s'];
    $data = date("Y-m-d H:i:s", strtotime("-3 hours"));
    $regiao = $_POST['regiao'];
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
                                   data_envio,
                                   regiao_preferencia_id
                                   )
                                   VALUES(
                                          '$idPF',
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
                                          '$data',
                                          '$regiao')";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        $idPC = recuperaUltimo('formacao_contratacoes');
        $protocolo = geraProtocolo($idPC) . '-F';
        $sqlProtcolo = "UPDATE formacao_contratacoes SET
                                        protocolo = '$protocolo' 
                                        WHERE id = '$idPC'";
        $queryProtocolo = mysqli_query($con, $sqlProtcolo);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

if (isset($_POST['editar'])) {
    $idPC = $_POST['idFC'];
    $sqlUpdate = "UPDATE formacao_contratacoes SET
                                 pessoa_fisica_id = '$idPF',
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
                                 suplente_id = '$suplente',
                                 regiao_preferencia_id = '$regiao'
                                WHERE id = '$idPC'";

    if (mysqli_query($con, $sqlUpdate)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

if (isset($_POST['edit'])) {
    $idPC = $_POST['idPCEdit'];
}

$fc = recuperaDados('formacao_contratacoes', 'id', $idPC);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Dados para Contratação</h2>
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
                                   value="<?= $fc['ano'] ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="chamado">Chamado: *</label>
                            <input type="number" min="0" max="127" id="chamado" name="chamado"
                                   value="<?= $fc['chamado'] ?>" required class="form-control">
                        </div>

                    </div>

                    <div class="row">
                        <div class="from-group col-md-12">
                            <label for="pf">Pessoa Física: *</label>
                            <select required name="idPF" id="idPF" class="form-control">
                                <?php
                                geraOpcao('pessoa_fisicas', $fc['pessoa_fisica_id']);
                                ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa: *</label>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-default"><i class="fa fa-info"></i></button>
                            <select class="form-control" name="classificacao" id="classificacao" required>
                                <?php
                                geraOpcao("classificacao_indicativas", $fc['classificacao']);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="territorio">Território: *</label>
                            <select class="form-control" name="territorio" id="territorio" required>
                                <?php
                                geraOpcao("territorios", $fc['territorio_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="coordenadoria">Coordenadoria: *</label>
                            <select class="form-control" name="coordenadoria" id="coordenadoria" required>
                                <?php
                                geraOpcao("coordenadorias", $fc['coordenadoria_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="subprefeitura">Subprefeitura: *</label>
                            <select class="form-control" name="subprefeitura" id="subprefeitura" required>
                                <?php
                                geraOpcao("subprefeituras", $fc['subprefeitura_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="programa">Programa: *</label>
                            <select class="form-control" name="programa" id="programa" required>
                                <?php
                                geraOpcao("programas", $fc['programa_id']);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="linguagem">Linguagem: *</label>
                            <select class="form-control" name="linguagem" id="linguagem" required>
                                <?php
                                geraOpcao("linguagens", $fc['linguagem_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="projeto">Projeto: *</label>
                            <select class="form-control" name="projeto" id="projeto" required>
                                <?php
                                geraOpcao("projetos", $fc['projeto_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cargo">Cargo: *</label>
                            <select class="form-control" name="cargo" id="cargo" required>
                                <?php
                                geraOpcao("formacao_cargos", $fc['form_cargo_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="vigencia">Vigência: *</label>
                            <select class="form-control" name="vigencia" id="vigencia" required>
                                <?php
                                $opcoesVigencia = $con->query("SELECT id, ano, descricao FROM formacao_vigencias WHERE publicado = 1");
                                if ($opcoesVigencia->num_rows > 0) {
                                    while ($opcoesArray = mysqli_fetch_row($opcoesVigencia)) {
                                        if ($opcoesArray[0] == $fc['form_vigencia_id']) { ?>
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
                        <div class="form-group col-md-4" id="msgEscondeAno">
                            <span style="color: red;"><b>Ano escolhido é maior que a vigência!</b></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="regiao">Região Preferencial: *</label>
                            <select class="form-control" name="regiao" id="regiao" required>
                                <?php
                                geraOpcao('regiao_preferencias', $fc['regiao_preferencia_id'])
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3"
                                      class="form-control"><?= $fc['observacao'] ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fiscal">Fiscal *</label>
                            <select name="fiscal" id="fiscal" class="form-control" required>
                                <option>Selecione um fiscal...</option>
                                <?php
                                geraOpcaoUsuario('usuarios', 1, $fc['fiscal_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fiscal">Suplente </label>
                            <select name="suplente" id="suplente" class="form-control">
                                <option>Selecione um suplente...</option>
                                <?php
                                geraOpcaoUsuario('usuarios', 1, $fc['suplente_id']);
                                ?>
                            </select>
                        </div>
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
            </form>
            <hr>
            <form action="?perfil=formacao&p=pedido_contratacao&sp=cadastra" method="POST">
                <input type="hidden" name="idFC" value="<?= $idPC ?>">
                <button style="width: 30%" type="submit" name="carregar" class="btn btn-success center-block">Gerar
                    pedido de contratação
                </button>
            </form>
        </div>
</div>
</section>
</div>


<?php @include "../perfil/includes/modal_classificacao.php" ?>


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
