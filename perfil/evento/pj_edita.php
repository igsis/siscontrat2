<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();
date_default_timezone_set('America/Sao_Paulo');

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do siscontrat
$http = $server . "/pdf/";
$link_facc = $http . "rlt_fac_pj.php";

$tipoPessoa = 2;

if (isset($_POST['idPj']) || isset($_POST['idProponente'])) {
    $idPj = $_POST['idPj'] ?? $_POST['idProponente'];
}

if (isset($_POST['editProponente'])) {
    $idPedido = $_SESSION['idPedido'];
    $voltar = "<form action='?perfil=evento&p=pedido_edita' method='post'>
                    <input type='hidden' name='idProponente' value='$idPj'>
                    <input type='hidden' name='tipoPessoa' value='$tipoPessoa'>
                        <button type='submit' name='idPedido' id='idPedido' value='$idPedido' class='btn btn-default'>Voltar</button>
                    </form>";
} else {
    $voltar = "<form action='?perfil=evento&p=pj_pesquisa' method='post'>
                        <button type='submit' class='btn btn-default'>Voltar</button>
                    </form>";
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

if (isset($_POST["enviar"])) {
    $idPj = $_POST['idPessoa'];
    $tipoPessoa = $_POST['tipoPessoa'];

    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1";
    $query_arquivos = mysqli_query($con, $sql_arquivos);

    while ($arq = mysqli_fetch_array($query_arquivos)) {
        $y = $arq['id'];
        $x = $arq['sigla'];
        $nome_arquivo = isset($_FILES['arquivo']['name'][$x]) ? $_FILES['arquivo']['name'][$x] : null;
        $f_size = isset($_FILES['arquivo']['size'][$x]) ? $_FILES['arquivo']['size'][$x] : null;

        if ($f_size > 5242880) {
            $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 05 MB.</strong>");

        } else {
            if ($nome_arquivo != "") {
                $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
                $new_name = date("YmdHis") . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                $hoje = date("Y-m-d H:i:s");
                $dir = '../uploadsdocs/'; //Diretório para uploads
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                $ext = strtolower(substr($nome_arquivo, -4));

                if (in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {
                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPj', '$y', '$new_name', '$hoje', '1'); ";
                        $query = mysqli_query($con, $sql_insere_arquivo);

                        if ($query) {
                            $mensagem = mensagem("success", "Arquivo recebido com sucesso");
                            echo "<script>
                                swal('Clique nos arquivos após efetuar o upload e confira a exibição do documento!', '', 'warning');                             
                            </script>";
                            gravarLog($sql_insere_arquivo);
                        } else {
                            $mensagem = mensagem("danger", "Erro ao gravar no banco");
                        }
                    } else {
                        $mensagem = mensagem("danger", "Erro no upload");
                    }
                } else {
                    echo "<script>
                            swal('Erro no upload! Anexar documentos somente no formato PDF.', '', 'error');                             
                        </script>";
                }
            }
        }
    }
}

if (isset($_POST['apagar'])) {
    $idArquivo = $_POST['idArquivo'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
    if (mysqli_query($con, $sql_apagar_arquivo)) {
        $arq = recuperaDados("arquivos", $idArquivo, "id");
        $mensagem = mensagem("success", "Arquivo " . $arq['arquivo'] . "apagado com sucesso!");
        gravarLog($sql_apagar_arquivo);
    } else {
        $mensagem = mensagem("danger", "Erro ao apagar o arquivo. Tente novamente!");
    }
}


$sqlTelefones = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$_SESSION['idPj'] = $idPj;

$pj = recuperaDados("pessoa_juridicas", "id", $idPj);
$end = recuperaDados("pj_enderecos", "pessoa_juridica_id", $idPj);
$obs = recuperaDados("pj_observacoes", "pessoa_juridica_id", $idPj);

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

                    <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="razao_social">Razão Social: *</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required value="<?= $pj['razao_social'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="cnpj">CNPJ: *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required readonly value="<?= $pj['cnpj'] ?>">

                                </div>
                                <div class="form-group col-md-4">
                                    <?php
                                    anexosNaPagina(22, $idPj, "modal-cnpj", "CNPJ");
                                    ?>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm"
                                           value="<?= $pj['ccm'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <?php
                                    if ($end['uf'] == "SP") {
                                        $sqlExistentes = "SELECT * FROM arquivos WHERE lista_documento_id = (43) AND origem_id = '$idPj' AND publicado = 1";
                                        $queryExistentes = mysqli_query($con, $sqlExistentes);
                                        $cpom = 0;
                                    } else {
                                        $sqlExistentes = "SELECT * FROM arquivos WHERE lista_documento_id = (28) AND origem_id = '$idPj' AND publicado = 1";
                                        $queryExistentes = mysqli_query($con, $sqlExistentes);
                                        $cpom = 1;
                                    }

                                    if (mysqli_num_rows($queryExistentes) == 0) {
                                        ?>
                                        <label>Anexo FDC - CCM ou CPOM</label><br>
                                        <button type="button" class="btn btn-primary btn-block" id="modal"
                                                data-toggle="modal" data-target="#modal-ccm">
                                            Clique aqui para anexar
                                        </button>
                                        <?php
                                    } elseif (mysqli_num_rows($queryExistentes) > 0 && $cpom == 0) {
                                        $arquivo = mysqli_fetch_array($queryExistentes);
                                        ?>
                                        <label>FDC - CCM anexado no dia: <?= exibirDataBr($arquivo['data']) ?></label>
                                        <br>
                                        <div class='form-group' style='display: flex; align-items: center;'>
                                            <button class='btn-sm btn-danger glyphicon glyphicon-trash' type='button'
                                                    data-toggle='modal'
                                                    data-target='#exclusao' data-id='<?= $arquivo['id'] ?>'
                                                    data-nome='<?= $arquivo['arquivo'] ?>'>
                                            </button> &nbsp;&nbsp;
                                            <a href='../uploadsdocs/<?= $arquivo['arquivo'] ?>' target='_blank'><?=
                                                mb_strimwidth($arquivo['arquivo'], 15, 25, '...') ?></a></div>
                                        <?php

                                    } else {
                                        $arquivo = mysqli_fetch_array($queryExistentes);
                                        ?>

                                        <label>CPOM anexado no dia: <?= exibirDataBr($arquivo['data']) ?></label>
                                        <br>
                                        <a class="link" href='../uploadsdocs/<?= $arquivo['arquivo'] ?>'
                                           target='_blank'><?= mb_strimwidth($arquivo['arquivo'], 15, 25, "...") ?></a>

                                        <?php
                                    }
                                    ?>
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
                                           class="form-control"
                                           id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="celular">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
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
                                               class="form-control"
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-3">
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
                                    <input type="number" name="numero" class="form-control" placeholder="Ex.: 10"
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
                            <div class="row">
                                <?php
                                $sqlFACC = "SELECT * FROM arquivos WHERE lista_documento_id = 89 AND origem_id = '$idPj' AND publicado = 1";
                                $queryFACC = mysqli_query($con, $sqlFACC);

                                $facc = "block";

                                if (mysqli_num_rows($queryFACC) == 0 && $pj['representante_legal1_id'] == null) {

                                    echo " <div class='form-group col-md-12 text-center'>
                                                   <label>&nbsp;</label><br> 
                                                   <h4 class='text-warning text-bold'><em>Para gerar a FACC primeiro cadastre um representante legal.</em></h4>
                                               </div>";

                                    $facc = "none";

                                } else if ($pj['representante_legal1_id'] != null) {
                                    ?>
                                    <div class="form-group col-md-3">
                                        <label>Gerar FACC</label><br>
                                        <a href="<?= $link_facc . "?id=" . $idPj ?>" target="_blank" type="button"
                                           class="btn btn-primary btn-block">Clique aqui para
                                            gerar a FACC
                                        </a>
                                    </div>
                                    <div class="form-group col-md-5" style="display: <?= $facc ?>">
                                        <label>&nbsp;</label><br>
                                        <p>A FACC deve ser impressa, datada e assinada nos campos indicados no
                                            documento. Logo após, deve-se digitaliza-la e então anexa-la ao sistema
                                            no campo correspondente.</p>
                                    </div>
                                    <div class="form-group col-md-4" style="display: <?= $facc ?>">
                                        <?php
                                        anexosNaPagina(89, $idPj, "modal-facc", "FACC");
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
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
                                        class="btn btn-info pull-right">Atualizar
                                </button>
                    </form>
                    <?= $voltar ?>
                </div>
            </div>
        </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-3">
                        <form method="POST" action="?perfil=evento&p=pj_demais_anexos" role="form">
                            <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                    class="btn btn-info btn-block">Demais Anexos
                            </button>
                        </form>
                    </div>

                    <?php
                    if ($pj['representante_legal1_id'] == null && $pj['representante_legal2_id'] == null) {
                        ?>
                        <div class="form-group col-md-3">
                            <form method="POST" action="?perfil=evento&p=representante_busca" role="form">
                                <input type="hidden" name="tipoRepresentante" value="1">
                                <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                        class="btn btn-info btn-block">Representante 01
                                </button>
                            </form>
                        </div>
                        <div class="form-group col-md-3">
                            <form method="POST" action="?perfil=evento&p=representante_busca" role="form">
                                <input type="hidden" name="tipoRepresentante" value="2">
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
                                    data-id="<?= $representante1['id'] ?>" data-nome="<?= $representante1['nome'] ?>">
                                Representante 01
                            </button>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                    class="btn btn-info btn-block" id="modal" data-toggle="modal"
                                    data-target="#modal-representante-edita" data-tipo="2"
                                    data-id="<?= $representante2['id'] ?>" data-nome="<?= $representante2['nome'] ?>">
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
                                    data-id="<?= $representante1['id'] ?>" data-nome="<?= $representante1['nome'] ?>">
                                Representante 01
                            </button>
                        </div>
                        <div class="form-group col-md-3">
                            <form method="POST" action="?perfil=evento&p=representante_busca" role="form">
                                <input type="hidden" name="tipoRepresentante" value="2">
                                <button type="submit" name="idPj" value="<?= $pj['id'] ?>"
                                        class="btn btn-info btn-block">
                                    Representante 02
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="form-group col-md-3">
                        <?php
                        $sqlPedidos = "SELECT * FROM pedidos WHERE publicado = 1";
                        $queryPedidos = mysqli_query($con, $sqlPedidos);
                        $pedidos = mysqli_fetch_array($queryPedidos);

                        if (($pedidos['pessoa_tipo_id'] == 2) && ($pedidos['pessoa_juridica_id'] == $idPj)) {

                            ?>
                            <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                                <input type="hidden" name="pessoa_tipo_id" value="2">
                                <input type="hidden" name="idPedido" value="<?= $pedidos['id']; ?>">
                                <input type="hidden" name="idProponente" value="<?= $pj['id'] ?>">
                                <input type="hidden" name="tipoPessoa" value="2">
                                <input type="hidden" name="tipoEvento" value="<?= $evento['tipo_evento_id']?>">
                                <button type="submit" name="carregar" class="btn btn-info btn-block">Ir ao pedido de
                                    contratação
                                </button>
                            </form>

                            <?php
                        } else {
                            ?>
                            <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                                <input type="hidden" name="pessoa_tipo_id" value="2">
                                <input type="hidden" name="pessoa_id" value="<?= $pj['id'] ?>">
                                <input type="hidden" name="valor" value="<?= $atracao['valor_individual'] ?>">
                                <input type="hidden" name="tipoEvento" value="<?= $evento['tipo_evento_id']?>">
                                <button type="submit" name="cadastra" class="btn btn-info btn-block">Ir ao pedido de
                                    contratação
                                </button>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- /. box-body -->
        </div>
    </div>
</div>
<?php
modalUploadArquivoUnico("modal-cnpj", "?perfil=evento&p=pj_edita", "CNPJ", "cartao_cnp", $idPj, "2");
modalUploadArquivoUnico("modal-facc", "?perfil=evento&p=pj_edita", "facc", "facc", $idPj, "2");
?>

</section>
</div>

<div class="modal fade" id="modal-ccm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Upload de FCD - CCM ou CPOM</h4>
            </div>
            <div class="modal-body">
                <p align='center'><strong>Arquivo somente em PDF e até 05 MB.</strong></p>
                <form method="POST" action="?perfil=evento&p=pj_edita" enctype="multipart/form-data">
                    <br/>
                    <div align='center'>
                        <?php
                        if ($end['uf'] == "SP") {
                        ?>
                        <label>FDC - CCM</label>
                        <input type='file' id="ccm" name='arquivo[ccm]'>
                    </div>
                    <?php
                    } else {
                        ?>
                        <div align='center'>
                            <label>CPOM</label>
                            <input type='file' id="cpom" name='arquivo[cpom]'>
                        </div>
                        <?php
                    }
                    ?>
                    <br/>
                    <input type="hidden" name="idPessoa" value="<?= $idPj ?>"/>
                    <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>"/>
            </div>
            <div class="modal-footer">
                <button type="submit" name="enviar" class="btn btn-success">Enviar</button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
            </div>
            </form>
        </div>
    </div>
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
                        <form method="POST" action="?perfil=evento&p=representante_edita" role="form">
                            <input type='hidden' name='idPj' id='idPj' value='<?= $idPj ?>'>
                            <input type='hidden' name='idRepresentante' id='idRepresentante' value=''>
                            <input type='hidden' name='tipoRepresentante' id='tipoRepresentante' value=''>
                            <button type="submit" name="abrirPag" class="btn btn-primary btn-block">
                                Editar Representante
                            </button>
                        </form>
                    </div>
                    <div class="form-group col-md-6"><label><br></label>
                        <form method="POST" action="?perfil=evento&p=representante_busca" role="form">
                            <input type='hidden' name='idPj' id='idPj' value='<?= $idPj ?>'>
                            <input type='hidden' name='tipoRepresentanteTroca' id='tipoRepresentanteTroca' value=''>
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
            </form>
        </div>
    </div>
</div>


<!--.modal-->
<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body text-center">
                <p>Tem certeza que deseja excluir este arquivo?</p>
            </div>
            <div class="modal-footer">
                <form action="?perfil=evento&p=pj_edita" method="post">
                    <input type="hidden" name="idArquivo" id="idArquivo" value="">
                    <input type="hidden" name="idPj" id="idPj" value="<?= $idPj ?>">
                    <input type="hidden" name="apagar" id="apagar">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                </form>
            </div>
        </div>
    </div>
</div>
<!--  Fim Modal de Upload de arquivo  -->

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
