<?php

$con = bancoMysqli();
$conn = bancoPDO();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do siscontrat
$http = $server . "/pdf/";
$link_facc = $http . "rlt_fac_pj.php";

$tipoPessoa = 2;

if (isset($_POST['adicionar']) || isset($_POST['idPj'])) {
    $idPj = $_POST['idPj'];
    $idPedido = $_POST['idPedido'];
    $editaOnly = "<input type='hidden' name='editOnly' value= '1'>";
}

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $razao_social = addslashes($_POST['razao_social']);
    $cnpj = $_POST['cnpj'];
    $ccm = $_POST['ccm'] ?? NULL;
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $cep = $_POST['cep'];
    $logradouro = addslashes($_POST['rua']);
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? NULL;
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $uf = $_POST['estado'];
    $banco = $_POST['banco'] ?? NULL;
    $agencia = $_POST['agencia'] ?? NULL;
    $conta = $_POST['conta'] ?? NULL;
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $ultima_atualizacao = date('Y-m-d H:i:s');
    $idPedido = $_POST['idPedido'];
}

if (isset($_POST['cadastra'])) {
    $mensagem = "";
    $sql = "INSERT INTO pessoa_juridicas (razao_social, cnpj, ccm, email, ultima_atualizacao) VALUES ('$razao_social', '$cnpj', '$ccm', '$email', '$ultima_atualizacao')";
    if (mysqli_query($con, $sql)) {
        $idPj = recuperaUltimo('pessoa_juridicas');
        // cadastrar o telefone de pj
        foreach ($telefones AS $telefone) {
            if (!empty($telefone)) {
                $sqlTel = "INSERT INTO pj_telefones (pessoa_juridica_id, telefone, publicado) VALUES ('$idPj', '$telefone', 1)";
                mysqli_query($con, $sqlTel);
            }
        }
        // cadastrar endereco de pj
        $sqlEndereco = "INSERT INTO pj_enderecos (pessoa_juridica_id, logradouro, numero, complemento, bairro, cidade, uf, cep) VALUES ('$idPj','$logradouro','$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep')";

        if (!mysqli_query($con, $sqlEndereco)) {
            $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlEndereco;
        }

        if ($banco != NULL) {
            $sqlBanco = "INSERT INTO pj_bancos (pessoa_juridica_id, banco_id, agencia, conta) VALUES ('$idPj', '$banco', '$agencia', '$conta')";
            if (!mysqli_query($con, $sqlBanco)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlBanco;
            }
        }

        if ($observacao != NULL) {
            $sqlObs = "INSERT INTO pj_observacoes (pessoa_juridica_id, observacao) VALUES ('$idPj','$observacao')";
            if (!mysqli_query($con, $sqlObs)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlObs;
            }
        }

        $mensagem .= mensagem("success", "Cadastrado com sucesso!");
        $idPj = recuperaUltimo("pessoa_juridicas");
        //gravarLog($sql);
    } else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $idPj = $_POST['edita'];

    $mensagem = "";
    $sql = "UPDATE pessoa_juridicas SET razao_social = '$razao_social', cnpj = '$cnpj', ccm = '$ccm', email = '$email' WHERE id = '$idPj'";

    if (mysqli_query($con, $sql)) {
        if (isset($_POST['telefone2'])) {
            $telefone2 = $_POST['telefone2'];
            $sqlTelefone2 = "INSERT INTO pj_telefones (pessoa_juridica_id, telefone) VALUES ('$idPj', '$telefone2')";
            $query = mysqli_query($con, $sqlTelefone2);
        }

        if (isset($_POST['telefone3'])) {
            $telefone3 = $_POST['telefone3'];
            $sqlTelefone3 = "INSERT INTO pj_telefones (pessoa_juridica_id, telefone) VALUES ('$idPj', '$telefone3')";
            $query = mysqli_query($con, $sqlTelefone3);
        }

        if (mysqli_query($con, $sql)) {

            foreach ($telefones as $idTelefone => $telefone) {

                if (!strlen($telefone)) {
                    // Deletar telefone do banco se for apagado.
                    $sqlDelete = "DELETE FROM pj_telefones WHERE id = '$idTelefone'";
                    mysqli_query($con, $sqlDelete);
                    gravarLog($sqlDelete);
                }

                if ($telefone != '') {
                    // editar o telefone de pj
                    $sqlTelefone = "UPDATE  pj_telefones SET
                                          telefone = '$telefone'
                                  WHERE id = '$idTelefone'";
                    mysqli_query($con, $sqlTelefone);
                    gravarLog($sqlTelefone);
                }
            }

            $sqlEndereco = "UPDATE pj_enderecos SET logradouro = '$logradouro', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', uf = '$uf', cep = '$cep' WHERE pessoa_juridica_id = '$idPj'";
            if (!mysqli_query($con, $sqlEndereco)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[E]") . $sqlEndereco;
            }

            $banco_existe = verificaExiste("pj_bancos", "pessoa_juridica_id", $idPj, 0);
            if ($banco_existe['numero'] > 0) {
                $sqlBanco = "UPDATE pj_bancos SET banco_id = '$banco', agencia = '$agencia', conta = '$conta' WHERE pessoa_juridica_id = '$idPj'";
                if (!mysqli_query($con, $sqlBanco)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlBanco;
                }
            } else {
                $sqlBanco = "INSERT INTO pj_bancos (pessoa_juridica_id, banco_id, agencia, conta) VALUES ('$idPj', '$banco', '$agencia', '$conta')";
                if (!mysqli_query($con, $sqlBanco)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Primeiro registre uma atracao, para entao fazer seu pedido.") . $sqlBanco;
                }
            }

            if ($observacao != NULL) {
                $obs_existe = verificaExiste("pj_observacoes", "pessoa_juridica_id", $idPj, 0);
                if ($obs_existe['numero'] > 0) {
                    $sqlObs = "UPDATE pj_observacoes SET observacao = '$observacao' WHERE pessoa_juridica_id = '$idPj'";
                    if (!mysqli_query($con, $sqlObs)) {
                        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlObs;
                    }
                } else {
                    $sqlObs = "INSERT INTO pj_observacoes (pessoa_juridica_id, observacao) VALUES ('$idPj','$observacao')";
                    if (!mysqli_query($con, $sqlObs)) {
                        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlObs;
                    }
                }
            }
            $mensagem .= mensagem("success", "Gravado com sucesso!");
        } else {
            $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
        }
    }
}


if (isset($_POST['load'])) {
    $idPj = $_POST['idPj'];
    $idPedido = $_POST['idPedido'];
}

$sqlTelefones = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$_SESSION['idPj'] = $idPj;

$pj = recuperaDados("pessoa_juridicas", "id", $idPj);
$end = recuperaDados("pj_enderecos", "pessoa_juridica_id", $idPj);
$obs = recuperaDados("pj_observacoes", "pessoa_juridica_id", $idPj);

$idEvento = $_SESSION['idEvento'];
$atracao = recuperaDados('atracoes', 'evento_id', $idEvento);

if (isset($pj['representante_legal1_id'])) {
    $representante1 = recuperaDados('representante_legais', 'id', $pj['representante_legal1_id']);
}

if (isset($pj['representante_legal2_id'])) {
    $representante2 = recuperaDados('representante_legais', 'id', $pj['representante_legal2_id']);
}
?>

<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
    });

</script>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro Pessoa Jurídica</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Pessoa Jurídica</h3>
                    </div>
                    <div class="row" align="center">
                        <?= $mensagem ?? NULL ?>
                    </div>

                    <form method="POST" action="?perfil=contrato&p=edita_pj" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="razao_social">Razão Social: *</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required value="<?= $pj['razao_social'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="cnpj">CNPJ: *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required readonly value="<?= $pj['cnpj'] ?>">

                                </div>

                                <div class="form-group col-md-6">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm"
                                           value="<?= $pj['ccm'] ?>">
                                </div>


                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60"
                                           placeholder="Digite o E-mail" required value="<?= $pj['email'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="telefone">Telefone #1 * </label>
                                    <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" required
                                           class="form-control" data-mask="(00) 00000-0000"
                                           id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="celular">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               id="telefone1" name="telefone1">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="recado">Telefone #3</label>
                                    <?php if (isset($arrayTelefones[2])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000"
                                           value="<?= $end['cep'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua"
                                           placeholder="Digite a rua" maxlength="200" readonly
                                           value="<?= $end['logradouro'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <i>(se não houver, marcar 0)</i>
                                    <input type="number" name="numero" class="form-control" min="0"
                                           placeholder="Ex.: 10"
                                           required value="<?= $end['numero'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento" value="<?= $end['complemento'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly
                                           value="<?= $end['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly
                                           value="<?= $end['cidade'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Ex.: SP" readonly value="<?= $end['uf'] ?>">
                                </div>
                            </div>
                            <hr/>
                            <?php
                            $banco = recuperaDados("pj_bancos", "pessoa_juridica_id", $idPj);
                            ?>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="banco">Banco:</label>
                                    <select id="banco" name="banco" class="form-control">
                                        <option value="">Selecione um banco...</option>
                                        <?php
                                        geraOpcao("bancos", $banco['banco_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="agencia">Agência:</label>
                                    <input type="text" name="agencia" class="form-control"
                                           placeholder="Digite a Agência" maxlength="12"
                                           value="<?= $banco['agencia'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="conta">Conta:</label>
                                    <input type="text" name="conta" class="form-control"
                                           placeholder="Digite a Conta" maxlength="12"
                                           value="<?= $banco['conta'] ?>">
                                </div>
                            </div>

                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea id="observacao" name="observacao" rows="3"
                                              class="form-control"><?= $obs['observacao'] ?? NULL ?></textarea>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" name="edita" value="<?= $pj['id'] ?>"
                                        class="btn btn-primary pull-right">Salvar
                                </button>
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">

                            </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if ($pj['representante_legal1_id'] == null) {
            $disabled = "disabled";
            echo "<div class='col-md-12' style='text-align:center'>
                    <span style='color: red;'><strong>Para retornar ao pedido é necessário cadastrar pelo menos um representante legal!</strong></span>
                  </div>";
        } else {
            $disabled = "";
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <?php
                                $sqlPedidos = "SELECT * FROM pedidos WHERE publicado = 1 AND origem_tipo_id = 1 AND origem_id = '$idEvento'";
                                $queryPedidos = mysqli_query($con, $sqlPedidos);
                                $pedidos = mysqli_fetch_array($queryPedidos); ?>
                                <form method="POST" action="?perfil=contrato&p=resumo" role="form">
                                    <input type="hidden" name="idPedido" value="<?= $pedidos['id'] ?>">
                                    <input type="hidden" name="idPj" value="<?= $pj['id'] ?>">
                                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                    <?= $editaOnly ?? NULL ?>
                                    <button type="submit" name="selecionarPj" <?= $disabled ?>
                                            class="btn btn-info btn-block"
                                            style="margin: 0 5px;">Voltar
                                    </button>
                                </form>
                            </div>
                            <div class="form-group col-md-3">
                                <form method="POST" action="?perfil=contrato&p=demais_anexos_pj"
                                      role="form">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                            class="btn btn-info btn-block">Demais Anexos
                                    </button>
                                </form>
                            </div>

                            <?php
                            if ($pj['representante_legal1_id'] == null && $pj['representante_legal2_id'] == null) {
                                ?>
                                <div class="form-group col-md-3">
                                    <form method="POST" action="?perfil=contrato&p=representante_busca"
                                          role="form">
                                        <input type="hidden" name="tipoRepresentante" value="1">
                                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                        <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                                class="btn btn-info btn-block">Representante 01
                                        </button>
                                    </form>
                                </div>
                                <div class="form-group col-md-3">
                                    <form method="POST" action="?perfil=contrato&p=representante_busca"
                                          role="form">
                                        <input type="hidden" name="tipoRepresentante" value="2">
                                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                        <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                                class="btn btn-info btn-block">Representante 02
                                        </button>
                                    </form>
                                </div>
                                <?php
                            } elseif ($pj['representante_legal1_id'] != null && $pj['representante_legal2_id'] != null) {
                                ?>
                                <div class="form-group col-md-3">
                                    <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                            class="btn btn-info btn-block" id="modal" data-toggle="modal"
                                            data-target="#modal-representante-edita" data-tipo="1"
                                            data-id="<?= $representante1['id'] ?>"
                                            data-nome="<?= $representante1['nome'] ?>">
                                        Representante 01
                                    </button>
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                            class="btn btn-info btn-block" id="modal" data-toggle="modal"
                                            data-target="#modal-representante-edita" data-tipo="2"
                                            data-id="<?= $representante2['id'] ?>"
                                            data-nome="<?= $representante2['nome'] ?>">
                                        Representante 02
                                    </button>
                                </div>
                                <?php
                            } elseif ($pj['representante_legal1_id'] != null) {
                                ?>
                                <div class="form-group col-md-3">
                                    <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                            class="btn btn-info btn-block"
                                            id="modal" data-toggle="modal" data-target="#modal-representante-edita"
                                            data-tipo="1"
                                            data-id="<?= $representante1['id'] ?>"
                                            data-nome="<?= $representante1['nome'] ?>">
                                        Representante 01
                                    </button>
                                </div>
                                <div class="form-group col-md-3">
                                    <form method="POST" action="?perfil=contrato&p=representante_busca"
                                          role="form">
                                        <input type="hidden" name="tipoRepresentante" value="2">
                                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                        <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                                class="btn btn-info btn-block">
                                            Representante 02
                                        </button>
                                    </form>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /. box-body -->
                </div>
            </div>
        </div>


    </section>
</div>

<div class="modal fade" id="modal-representante-edita" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Representante Legal</h4>
            </div>
            <div class="modal-body text-center">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="proponente">Representante cadastrado</label>
                        <br><input type="text" id="representante" name="representante" class="form-control text-center"
                                   disabled
                                   value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6"><label><br></label>
                        <form method="POST" action="?perfil=contrato&p=representante_edita"
                              role="form">
                            <input type='hidden' name='idPj' id='idPj' value='<?= $idPj ?>'>
                            <input type='hidden' name='idRepresentante' id='idRepresentante' value=''>
                            <input type='hidden' name='tipoRepresentante' id='tipoRepresentante' value=''>
                            <input type="hidden" name="idPedido" value="<?= $pedidos['id'] ?>">
                            <button type="submit" name="abrirPag" class="btn btn-primary btn-block">
                                Editar Representante
                            </button>
                        </form>
                    </div>
                    <div class="form-group col-md-6"><label><br></label>
                        <form method="POST" action="?perfil=contrato&p=representante_busca"
                              role="form">
                            <input type='hidden' name='idPj' id='idPj' value='<?= $idPj ?>'>
                            <input type='hidden' name='tipoRepresentanteTroca' id='tipoRepresentanteTroca' value=''>
                            <input type="hidden" name="idPedido" value="<?= $pedidos['id'] ?>">
                            <button type="submit" name="trocar" class="btn btn-primary btn-block">Trocar de
                                Representante
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="cadastra" class="btn btn-success">Cadastrar</button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);

    });


    $('#modal-representante-edita').on('show.bs.modal', function (e) {
        let representante = $(e.relatedTarget).attr('data-nome');
        let idRepresentante = $(e.relatedTarget).attr('data-id');
        let tipoRepresentante = $(e.relatedTarget).attr('data-tipo');

        $(this).find('#representante').attr('value', `${representante}`);
        $(this).find('#idRepresentante').attr('value', `${idRepresentante}`);
        $(this).find('#tipoRepresentante').attr('value', `${tipoRepresentante}`);
        $(this).find('#tipoRepresentanteTroca').attr('value', `${tipoRepresentante}`);

        console.log(tipoRepresentante);

    });

</script>

